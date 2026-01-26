/**
 * Système de drag-and-drop pour les Recap (parties et blocs)
 * Gère la réorganisation des RecapPart (avec boutons) et RecapPartBlock (drag & drop)
 */

class RecapDragDrop {
    constructor(recapId) {
        this.recapId = recapId;
        this.sortableInstances = [];
        this.isReorderMode = false;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Initialise le système de réorganisation
     */
    init() {
        const toggleButton = document.getElementById('toggle-reorder-mode');
        if (!toggleButton) {
            console.warn('RecapDragDrop: Bouton toggle non trouvé');
            return;
        }

        toggleButton.addEventListener('click', () => this.toggleReorderMode());
    }

    /**
     * Active/désactive le mode réorganisation
     */
    toggleReorderMode() {
        this.isReorderMode = !this.isReorderMode;

        const toggleButton = document.getElementById('toggle-reorder-mode');
        const dragHandles = document.querySelectorAll('.drag-handle');
        const partButtons = document.querySelectorAll('.part-move-buttons');

        if (this.isReorderMode) {
            // Activer le mode
            toggleButton.classList.remove('bg-blue-500', 'hover:bg-blue-700');
            toggleButton.classList.add('bg-green-500', 'hover:bg-green-700');
            toggleButton.textContent = 'Terminer la réorganisation';

            // Montrer les contrôles de réorganisation
            dragHandles.forEach(handle => handle.classList.remove('hidden'));
            partButtons.forEach(buttons => buttons.classList.remove('hidden'));

            // Initialiser Sortable pour chaque partie
            this.initSortableBlocks();
        } else {
            // Désactiver le mode
            toggleButton.classList.remove('bg-green-500', 'hover:bg-green-700');
            toggleButton.classList.add('bg-blue-500', 'hover:bg-blue-700');
            toggleButton.textContent = 'Réorganiser';

            // Cacher les contrôles
            dragHandles.forEach(handle => handle.classList.add('hidden'));
            partButtons.forEach(buttons => buttons.classList.add('hidden'));

            // Détruire les instances Sortable
            this.destroySortableInstances();
        }
    }

    /**
     * Initialise Sortable pour tous les conteneurs de blocs
     */
    initSortableBlocks() {
        document.querySelectorAll('.sortable-blocks').forEach(container => {
            const sortable = new Sortable(container, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-blue-100',
                onEnd: (evt) => this.onBlockReorder(evt)
            });
            this.sortableInstances.push(sortable);
        });
    }

    /**
     * Détruit toutes les instances Sortable
     */
    destroySortableInstances() {
        this.sortableInstances.forEach(instance => instance.destroy());
        this.sortableInstances = [];
    }

    /**
     * Appelé quand un bloc est réorganisé par drag & drop
     */
    async onBlockReorder(evt) {
        const container = evt.to;
        const blocks = Array.from(container.querySelectorAll('.block-item')).map(item => {
            return item.dataset.blockId;
        });

        this.showLoadingState(container);

        try {
            const response = await fetch('/admin/recapPartBlock/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ blocks: blocks })
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess('Blocs réorganisés avec succès');
            } else {
                this.showError('Erreur lors de la réorganisation');
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Erreur de connexion');
            location.reload();
        } finally {
            this.hideLoadingState(container);
        }
    }

    /**
     * Déplace une partie vers le haut
     */
    async movePartUp(partId, button) {
        button.disabled = true;
        this.showLoadingState(button.closest('.flex'));

        try {
            const response = await fetch(`/admin/recapPart/${partId}/moveUp`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess('Partie déplacée vers le haut');
                setTimeout(() => location.reload(), 500);
            } else {
                this.showError(data.message || 'Erreur lors du déplacement');
                button.disabled = false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Erreur de connexion');
            button.disabled = false;
        } finally {
            this.hideLoadingState(button.closest('.flex'));
        }
    }

    /**
     * Déplace une partie vers le bas
     */
    async movePartDown(partId, button) {
        button.disabled = true;
        this.showLoadingState(button.closest('.flex'));

        try {
            const response = await fetch(`/admin/recapPart/${partId}/moveDown`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess('Partie déplacée vers le bas');
                setTimeout(() => location.reload(), 500);
            } else {
                this.showError(data.message || 'Erreur lors du déplacement');
                button.disabled = false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Erreur de connexion');
            button.disabled = false;
        } finally {
            this.hideLoadingState(button.closest('.flex'));
        }
    }

    /**
     * Affiche l'état de chargement
     */
    showLoadingState(element) {
        element.style.opacity = '0.5';
        element.style.pointerEvents = 'none';
    }

    /**
     * Cache l'état de chargement
     */
    hideLoadingState(element) {
        element.style.opacity = '1';
        element.style.pointerEvents = 'auto';
    }

    /**
     * Affiche un message de succès
     */
    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    /**
     * Affiche un message d'erreur
     */
    showError(message) {
        this.showNotification(message, 'error');
    }

    /**
     * Affiche une notification
     */
    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Instance globale (sera initialisée dans la vue)
window.RecapDragDrop = RecapDragDrop;
