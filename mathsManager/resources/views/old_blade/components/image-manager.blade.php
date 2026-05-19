@props([
    'name' => 'images',
    'label' => 'Images',
    'context' => '',
    'identifier' => '',
    'prefix' => 'img-',
    'existingImages' => [],
    'isPublic' => true,
    'hideLatex' => false,
])

@php
    $preparedImages = collect($existingImages)->map(function($img) {
        return [
            'name' => is_string($img) ? $img : $img['name'],
            'path' => is_string($img) ? $img : $img['path'],
            'markedForDeletion' => false
        ];
    })->unique('name')->values()->toArray();

    $isOpenDefault = count($preparedImages) > 0 ? 'false' : 'true';
@endphp

<div class="image-manager-wrapper"
     id="image-manager-{{ $name }}"
     x-data="imageManager{{ $name }}()">
    <!-- Header accordion -->
    <button
        type="button"
        @click="isOpen = !isOpen"
        class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 border border-indigo-200 rounded-lg transition-all duration-200">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="font-semibold text-indigo-900">{{ $label }}</span>
            <span class="text-xs bg-indigo-200 text-indigo-800 px-2.5 py-1 rounded-full font-medium" x-text="totalImages + ' image' + (totalImages !== 1 ? 's' : '')"></span>
        </div>
        <svg
            class="w-5 h-5 text-indigo-600 transition-transform duration-200"
            :class="{ 'rotate-180': isOpen }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Content accordion -->
    <div x-show="isOpen" x-transition class="mt-3 border border-indigo-100 rounded-lg p-4 bg-white shadow-sm">
        <!-- Liste des images existantes -->
        <div class="space-y-2 mb-4" x-show="existingImages.length > 0">
            <template x-for="image in existingImages" :key="image.name">
                <div class="flex items-center gap-3 p-3 rounded-lg border-2 transition-all duration-200"
                     :class="image.markedForDeletion ? 'bg-red-50 border-red-300 opacity-60' : 'bg-slate-50 border-slate-200 hover:border-indigo-300'">

                    <!-- Thumbnail -->
                    <img :src="getImageUrl(image)" :alt="image.name" class="w-14 h-14 object-cover rounded-md border-2 border-white shadow-sm">

                    <!-- Nom -->
                    <code class="flex-1 text-sm font-mono bg-white px-3 py-2 rounded border border-slate-200" x-text="image.name"></code>

                    @if (!$hideLatex)
                    <!-- Bouton copier LaTeX -->
                    <button type="button" @click="copyLatex(image.name)"
                        class="p-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors duration-200 shadow-sm"
                        title="Copier LaTeX">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    @endif

                    <!-- Bouton supprimer/annuler -->
                    <button type="button" @click="toggleDeletion(image.name)"
                        class="p-2 rounded-lg transition-colors duration-200 shadow-sm"
                        :class="image.markedForDeletion ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-rose-500 hover:bg-rose-600 text-white'"
                        :title="image.markedForDeletion ? 'Annuler la suppression' : 'Marquer pour suppression'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- Nouvelles images à uploader -->
        <div class="space-y-2 mb-4" x-show="newImages.length > 0">
            <template x-for="(newImage, index) in newImages" :key="newImage.uniqueId">
                <div class="flex items-center gap-3 p-3 rounded-lg border-2 border-emerald-300 bg-emerald-50 transition-all duration-200">

                    <!-- Preview -->
                    <img :src="newImage.preview" class="w-14 h-14 object-cover rounded-md border-2 border-white shadow-sm">

                    <!-- Nom généré -->
                    <code class="flex-1 text-sm font-mono bg-white px-3 py-2 rounded border border-emerald-200" x-text="newImage.generatedName"></code>

                    <!-- Badge nouveau -->
                    <span class="px-3 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-full">Nouveau</span>

                    @if (!$hideLatex)
                    <!-- Bouton copier LaTeX -->
                    <button type="button" @click="copyLatex(newImage.generatedName)"
                        class="p-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors duration-200 shadow-sm"
                        title="Copier LaTeX">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    @endif

                    <!-- Bouton retirer -->
                    <button type="button" @click="removeNewImage(index)"
                        class="p-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors duration-200 shadow-sm"
                        title="Retirer cette image">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- Bouton ajouter des images -->
        <label class="flex items-center justify-center gap-3 p-4 border-2 border-dashed border-indigo-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 cursor-pointer group">
            <input type="file" @change="handleFileSelect($event)" accept="image/*" multiple class="hidden" id="fileInput-{{ $name }}">
            <svg class="w-6 h-6 text-indigo-500 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-sm font-semibold text-indigo-600 group-hover:text-indigo-700 transition-colors">Ajouter des images</span>
        </label>

        @if (!$hideLatex)
        <!-- Feedback copie -->
        <div x-show="copiedImage" x-transition
            class="mt-3 p-3 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-lg text-sm text-center font-medium">
            ✓ Code LaTeX copié !
        </div>

        <!-- Aide compacte -->
        <div class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded text-xs text-blue-700 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Cliquez sur le bouton copier pour obtenir le code LaTeX</span>
        </div>
        @endif
    </div>

    <!-- Hidden inputs pour les suppressions -->
    <template x-for="image in existingImages.filter(img => img.markedForDeletion)" :key="'del-' + image.name">
        <input type="hidden" name="delete_{{ $name }}[]" :value="image.name">
    </template>

    <!-- Container pour les inputs file des nouvelles images -->
    <div id="hiddenFilesContainer-{{ $name }}" style="display: none;"></div>
</div>

<script>
function imageManager{{ $name }}() {
    return {
        isOpen: {{ $isOpenDefault }},
        existingImages: @json($preparedImages),
        newImages: [],
        copiedImage: null,
        prefix: '{{ $prefix }}',
        nameAttr: '{{ $name }}',
        allFiles: [], // Stocke tous les fichiers sélectionnés

        get totalImages() {
            return this.existingImages.filter(img => !img.markedForDeletion).length + this.newImages.length;
        },

        getImageUrl(image) {
            if (image.path && image.path.includes('corrections/')) {
                const parts = image.path.split('/');
                return '/private/' + parts[0] + '/' + parts[1] + '/' + parts[2];
            }
            return '/storage/' + (image.path || image.name);
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            const startIndex = this.allFiles.length;

            // Trouver le numéro max parmi les images existantes (non supprimées)
            let maxImageNumber = 0;
            this.existingImages.forEach(img => {
                if (!img.markedForDeletion) {
                    const match = img.name.match(/img-(\d+)/);
                    if (match) {
                        maxImageNumber = Math.max(maxImageNumber, parseInt(match[1]));
                    }
                }
            });

            // Tenir compte des images déjà ajoutées dans cette session
            this.newImages.forEach(img => {
                const match = img.generatedName.match(/img-(\d+)/);
                if (match) {
                    maxImageNumber = Math.max(maxImageNumber, parseInt(match[1]));
                }
            });

            files.forEach((file, fileIndex) => {
                const nextIndex = maxImageNumber + fileIndex + 1;
                const generatedName = this.prefix + nextIndex;
                const uniqueId = Date.now() + '-' + (startIndex + fileIndex);

                // Ajouter le fichier à la liste globale
                this.allFiles.push(file);

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.newImages.push({
                        file: file,
                        preview: e.target.result,
                        generatedName: generatedName,
                        uniqueId: uniqueId,
                        arrayIndex: startIndex + fileIndex
                    });
                };
                reader.readAsDataURL(file);
            });

            // Créer un nouvel input file avec TOUS les fichiers accumulés
            this.updateHiddenInput();
            event.target.value = '';
        },

        updateHiddenInput() {
            const container = document.getElementById('hiddenFilesContainer-{{ $name }}');
            container.innerHTML = ''; // Clear existing

            if (this.allFiles.length > 0) {
                const dataTransfer = new DataTransfer();
                this.allFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });

                const input = document.createElement('input');
                input.type = 'file';
                input.name = this.nameAttr + '[]';
                input.multiple = true;
                input.files = dataTransfer.files;
                container.appendChild(input);
            }
        },

        removeNewImage(index) {
            const removed = this.newImages.splice(index, 1)[0];
            this.allFiles.splice(removed.arrayIndex, 1);

            // Recalculer les indices
            this.newImages.forEach((img, idx) => {
                img.arrayIndex = idx;
            });

            this.updateHiddenInput();
        },

        toggleDeletion(imageName) {
            const image = this.existingImages.find(img => img.name === imageName);
            if (image) {
                image.markedForDeletion = !image.markedForDeletion;
            }
        },

        copyLatex(imageName) {
            const latex = '\\graph{' + imageName + '}{0.5}{Description}';
            navigator.clipboard.writeText(latex).then(() => {
                this.copiedImage = imageName;
                setTimeout(() => { this.copiedImage = null; }, 2000);
            }).catch(err => { alert('Erreur de copie'); });
        }
    };
}
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
