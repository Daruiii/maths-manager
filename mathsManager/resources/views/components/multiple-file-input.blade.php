@props(['disabled' => false])

{{-- tout mettre en ligne --}}
<label>
    Images :
</label>
<div class="flex items-center gap-4">
<label class="custom-file-upload" for="file1">
    1
    <div class="image-container">
        <div class="carousel">
            <div class="carousel-inner"></div>
        </div>
        <div class="add-icon">+</div>
    </div>
    <input type="file" id="file1" name="images[]" accept="image/jpeg, image/png, image/jpg, image/gif, image/svg" style="display: none;">
</label>

<label class="custom-file-upload" for="file2">
    2
    <div class="image-container">
        <div class="carousel">
            <div class="carousel-inner"></div>
        </div>
        <div class="add-icon">+</div>
    </div>
    <input type="file" id="file2" name="images[]" accept="image/jpeg, image/png, image/jpg, image/gif, image/svg" style="display: none;">
</label>

<label class="custom-file-upload" for="file3">
    3
    <div class="image-container">
        <div class="carousel">
            <div class="carousel-inner"></div>
        </div>
        <div class="add-icon">+</div>
    </div>
    <input type="file" id="file3" name="images[]" accept="image/jpeg, image/png, image/jpg, image/gif, image/svg" style="display: none;">
</label>

<label class="custom-file-upload" for="file4">
    4
    <div class="image-container">
        <div class="carousel">
            <div class="carousel-inner"></div>
        </div>
        <div class="add-icon">+</div>
    </div>
    <input type="file" id="file4" name="images[]" accept="image/jpeg, image/png, image/jpg, image/gif, image/svg" style="display: none;">
</label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function(event) {
            const files = event.target.files;
            console.log(files);
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
