@props(['name' => 'images', 'label'])


{{-- Affichage des fichiers et gestion des inputs --}}
<label>
    {{ $label ?? 'Images :' }}
</label>
<div class="flex items-center gap-4">
    @for ($i = 1; $i <= 4; $i++)
        @php
            $inputId = $name . '_file' . $i; // Génère un id unique basé sur le name et l'indice
        @endphp
        <label class="custom-file-upload" for="{{ $inputId }}">
            {{ $i }}
            <div class="image-container">
                <div class="carousel">
                    <div class="carousel-inner"></div>
                </div>
                <div class="add-icon">+</div>
            </div>
            <input 
                type="file" 
                id="{{ $inputId }}" 
                name="{{ $name }}[]" 
                accept="image/jpeg, image/png, image/jpg, image/gif, image/svg" 
                style="display: none;">
        </label>
    @endfor
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(function(input) {
            input.addEventListener('change', function(event) {
                const files = event.target.files;
                const label = input.parentElement;
                const carouselInner = label.querySelector('.carousel-inner');
                
                // Nettoyage du conteneur pour éviter les doublons
                carouselInner.innerHTML = '';

                Array.from(files).forEach(function(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Vérifie si l'image est déjà dans le carousel
                        if (!carouselInner.querySelector(`img[src="${e.target.result}"]`)) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('carousel-item');
                            carouselInner.appendChild(img);
                        }
                    };
                    reader.readAsDataURL(file);
                });

                // Masque l'icône "+" une fois qu'une image est sélectionnée
                label.querySelector('.add-icon').style.display = files.length > 0 ? 'none' : 'flex';
            });
        });
    });
</script>

<style>
    .custom-file-upload {
        display: inline-block;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .image-container {
        display: flex;
        align-items: center;
    }

    .add-icon {
        width: 100px;
        height: 100px;
        border: 2px dashed #4E4FEB;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 48px;
        color: #4E4FEB;
        margin-left: 10px;
    }

    .carousel {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }

    .carousel-inner {
        display: flex;
        scroll-snap-type: x mandatory;
    }

    .carousel-item {
        scroll-snap-align: center;
        flex: 0 0 auto;
        width: 100px;
        height: 100px;
        margin-right: 10px;
    }

    .carousel-item.active {
        border: 2px solid #4E4FEB;
    }
</style>
