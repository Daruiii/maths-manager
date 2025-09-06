/**
 * Système de drag-and-drop multi-niveaux pour Maths Manager
 * Gère le déplacement de classes, chapitres et sous-chapitres
 * avec preview d'impact et confirmations
 */

class MultiLevelDragDrop {
    constructor() {
        this.sortableInstances = [];
        this.isReorderMode = false;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Initialise le drag-and-drop pour un niveau donné
     */
    initLevel(config) {
        const {
            containerId,
            handleClass,
            buttonId,
            itemClass,
            reorderRoute,
            level = 'chapter',
            previewRoute = null
        } = config;

        const container = document.getElementById(containerId);
        const button = document.getElementById(buttonId);

        if (!container || !button) {
            console.warn(`MultiLevelDragDrop: Container ${containerId} ou button ${buttonId} non trouvé`);
            return;
        }

        button.addEventListener('click', () => {
            this.toggleReorderMode(container, button, handleClass, reorderRoute, level, itemClass, previewRoute);
        });
    }

    /**
     * Active/désactive le mode réorganisation
     */
    toggleReorderMode(container, button, handleClass, reorderRoute, level, itemClass, previewRoute) {
        this.isReorderMode = !this.isReorderMode;

        if (this.isReorderMode) {
            this.enableReorderMode(container, button, handleClass, reorderRoute, level, itemClass, previewRoute);
        } else {
            this.disableReorderMode(button, handleClass);
        }
    }

    /**
     * Active le mode réorganisation
     */
    enableReorderMode(container, button, handleClass, reorderRoute, level, itemClass, previewRoute) {
        // Changer le style du bouton
        button.classList.remove('bg-blue-500', 'hover:bg-blue-700');
        button.classList.add('bg-green-500', 'hover:bg-green-700');
        button.textContent = 'Terminer la réorganisation';

        // Montrer les drag handles
        document.querySelectorAll(`.${handleClass}`).forEach(handle => {
            handle.classList.remove('hidden');
        });

        // Créer l'instance Sortable
        const sortable = new Sortable(container, {
            animation: 150,
            handle: `.${handleClass}`,
            onStart: (evt) => this.onDragStart(evt, level, previewRoute),
            onEnd: (evt) => this.onDragEnd(evt, reorderRoute, level, itemClass)
        });

        this.sortableInstances.push(sortable);
    }

    /**
     * Désactive le mode réorganisation
     */
    disableReorderMode(button, handleClass) {
        // Restaurer le style du bouton
        button.classList.remove('bg-green-500', 'hover:bg-green-700');
        button.classList.add('bg-blue-500', 'hover:bg-blue-700');
        button.textContent = button.dataset.originalText || 'Réorganiser';

        // Cacher les drag handles
        document.querySelectorAll(`.${handleClass}`).forEach(handle => {
            handle.classList.add('hidden');
        });

        // Détruire les instances Sortable
        this.sortableInstances.forEach(instance => instance.destroy());
        this.sortableInstances = [];

        // Recharger la page pour voir l'ordre mis à jour
        location.reload();
    }

    /**
     * Appelé au début du drag
     */
    async onDragStart(evt, level, previewRoute) {
        if (!previewRoute) return;

        const itemId = this.extractId(evt.item.id);
        
        try {
            const response = await fetch(previewRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    type: level,
                    id: itemId
                })
            });

            const data = await response.json();
            this.showPreviewTooltip(evt.item, data.affected_exercises, data.warning_level);
        } catch (error) {
            console.error('Erreur lors du preview:', error);
        }
    }

    /**
     * Appelé à la fin du drag
     */
    async onDragEnd(evt, reorderRoute, level, itemClass) {
        this.hidePreviewTooltip();

        const container = evt.to;
        const items = container.querySelectorAll(`.${itemClass}`);
        const orderData = [];

        items.forEach((item, index) => {
            const itemId = this.extractId(item.id);
            orderData.push({
                id: itemId,
                order: index + 1
            });
        });

        // Confirmation pour les gros changements
        const totalAffected = await this.getTotalAffectedExercises(orderData, level);
        if (totalAffected > 50) {
            const confirm = await this.showConfirmation(
                `Cette réorganisation va affecter ${totalAffected} exercices. Continuer ?`
            );
            if (!confirm) {
                location.reload(); // Annuler en rechargeant
                return;
            }
        }

        this.showLoadingState(container);

        try {
            const response = await fetch(reorderRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(this.buildReorderPayload(orderData, level))
            });

            const data = await response.json();
            
            if (data.status === 'success') {
                this.showSuccess('Réorganisation réussie !');
            } else {
                this.showError(data.message || 'Erreur lors de la réorganisation');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showError('Erreur de connexion');
        } finally {
            this.hideLoadingState(container);
        }
    }

    /**
     * Construit le payload selon le type de réorganisation
     */
    buildReorderPayload(orderData, level) {
        switch (level) {
            case 'chapter':
                return {
                    class_id: this.getCurrentClassId(),
                    chapter_orders: orderData
                };
            case 'subchapter':
                return {
                    chapter_id: this.getCurrentChapterId(),
                    subchapter_orders: orderData
                };
            case 'class':
                return {
                    class_orders: orderData.map(item => ({
                        id: item.id,
                        display_order: item.order
                    }))
                };
            default:
                return { orders: orderData };
        }
    }

    /**
     * Extrait l'ID depuis un attribut id HTML
     */
    extractId(htmlId) {
        return htmlId.split('-').pop();
    }

    /**
     * Obtient l'ID de la classe courante
     */
    getCurrentClassId() {
        // À adapter selon votre structure
        return window.currentClassId || document.body.dataset.classId;
    }

    /**
     * Obtient l'ID du chapitre courant
     */
    getCurrentChapterId() {
        // À adapter selon votre structure
        return window.currentChapterId || document.body.dataset.chapterId;
    }

    /**
     * Affiche un tooltip de preview pendant le drag
     */
    showPreviewTooltip(element, affectedCount, warningLevel) {
        const tooltip = document.createElement('div');
        tooltip.id = 'drag-preview-tooltip';
        tooltip.className = `fixed z-50 px-3 py-2 text-sm rounded shadow-lg pointer-events-none ${
            warningLevel === 'high' ? 'bg-red-500 text-white' :
            warningLevel === 'medium' ? 'bg-yellow-500 text-black' :
            'bg-blue-500 text-white'
        }`;
        tooltip.textContent = `${affectedCount} exercices affectés`;
        
        document.body.appendChild(tooltip);

        // Suivre la souris
        const updateTooltipPosition = (e) => {
            tooltip.style.left = e.clientX + 10 + 'px';
            tooltip.style.top = e.clientY - 30 + 'px';
        };

        document.addEventListener('mousemove', updateTooltipPosition);
        this._tooltipMouseHandler = updateTooltipPosition;
    }

    /**
     * Cache le tooltip de preview
     */
    hidePreviewTooltip() {
        const tooltip = document.getElementById('drag-preview-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
        if (this._tooltipMouseHandler) {
            document.removeEventListener('mousemove', this._tooltipMouseHandler);
            this._tooltipMouseHandler = null;
        }
    }

    /**
     * Affiche l'état de chargement
     */
    showLoadingState(container) {
        const overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.className = 'absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40';
        overlay.innerHTML = '<div class="text-white">Réorganisation en cours...</div>';
        
        container.style.position = 'relative';
        container.appendChild(overlay);
    }

    /**
     * Cache l'état de chargement
     */
    hideLoadingState(container) {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }

    /**
     * Affiche une confirmation
     */
    showConfirmation(message) {
        return new Promise((resolve) => {
            const result = confirm(message);
            resolve(result);
        });
    }

    /**
     * Affiche un message de succès
     */
    showSuccess(message) {
        // À adapter à votre système de notifications
        console.log('✅', message);
    }

    /**
     * Affiche un message d'erreur
     */
    showError(message) {
        // À adapter à votre système de notifications
        console.error('❌', message);
        alert(message);
    }

    /**
     * Estime le nombre total d'exercices affectés
     */
    async getTotalAffectedExercises(orderData, level) {
        // Implémentation simplifiée - vous pouvez l'améliorer
        return orderData.length * 10; // Estimation
    }
}

// Instance globale
window.MultiLevelDragDrop = new MultiLevelDragDrop();
