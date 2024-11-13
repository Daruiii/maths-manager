@props(['name'])

<label>Mes photos :</label>
<div class="flex items-center gap-4 flex-wrap" id="imageUploadContainer">
    <label class="custom-file-upload" for="file1">
        <span>1</span>
        <div class="image-container">
            <div class="carousel">
                <div class="carousel-inner"></div>
            </div>
            <div class="add-icon">+</div>
        </div>
        <input type="file" id="file1" name="{{ $name }}[]" accept="image/jpeg, image/png, image/jpg, image/gif, image/svg" class="image-upload-input" style="display: none;">
    </label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('.image-upload-input'); // Cible seulement les inputs d'images
        fileInputs.forEach(function(input) {
            input.addEventListener('change', function(event) {
                generateNewLabel();
                const files = event.target.files;
                const label = input.parentElement;
                const carouselInner = label.querySelector('.carousel-inner');
                carouselInner.innerHTML = ''; // Efface les images existantes

                Array.from(files).forEach(function(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('carousel-item');
                        carouselInner.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });

                // Masque le symbole "+" une fois que l'image est sélectionnée
                label.querySelector('.add-icon').style.display = 'none';
            });
        });
    });

    function generateNewLabel() {
        const fileInputs = document.querySelectorAll('.image-upload-input'); // Cible seulement les inputs d'images
        const lastFileInput = fileInputs[fileInputs.length - 1];
        const lastFileInputId = lastFileInput.getAttribute('id');
        const lastFileInputName = lastFileInput.getAttribute('name');
        const lastFileInputIndex = parseInt(lastFileInputId.replace('file', ''));
        const newFileInputIndex = lastFileInputIndex + 1;
        const newFileInputId = 'file' + newFileInputIndex;
        const newFileInputLabel = document.createElement('label');
        newFileInputLabel.setAttribute('class', 'custom-file-upload');
        newFileInputLabel.setAttribute('for', newFileInputId);
        newFileInputLabel.innerHTML = `<span>${newFileInputIndex}</span>`;

        const newImageContainer = document.createElement('div');
        newImageContainer.setAttribute('class', 'image-container');

        const newCarousel = document.createElement('div');
        newCarousel.setAttribute('class', 'carousel');
        const newCarouselInner = document.createElement('div');
        newCarouselInner.setAttribute('class', 'carousel-inner');
        newCarousel.appendChild(newCarouselInner);

        const newAddIcon = document.createElement('div');
        newAddIcon.setAttribute('class', 'add-icon');
        newAddIcon.innerHTML = '+';

        newImageContainer.appendChild(newCarousel);
        newImageContainer.appendChild(newAddIcon);

        newFileInputLabel.appendChild(newImageContainer);

        const newFileInput = document.createElement('input');
        newFileInput.setAttribute('type', 'file');
        newFileInput.setAttribute('id', newFileInputId);
        newFileInput.setAttribute('name', lastFileInputName);
        newFileInput.setAttribute('accept', 'image/jpeg, image/png, image/jpg, image/gif, image/svg');
        newFileInput.setAttribute('class', 'image-upload-input'); // Ajoute la classe ici
        newFileInput.setAttribute('style', 'display: none;');

        newFileInputLabel.appendChild(newFileInput);
        lastFileInput.parentElement.insertAdjacentElement('afterend', newFileInputLabel);

        newFileInput.addEventListener('change', function(event) {
            generateNewLabel();
            const files = event.target.files;
            const label = newFileInput.parentElement;
            const carouselInner = label.querySelector('.carousel-inner');
            carouselInner.innerHTML = ''; // Efface les images existantes

            Array.from(files).forEach(function(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('carousel-item');
                    carouselInner.appendChild(img);
                };
                reader.readAsDataURL(file);
            });

            // Masque le symbole "+" une fois que l'image est sélectionnée
            label.querySelector('.add-icon').style.display = 'none';
        });
    }
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
