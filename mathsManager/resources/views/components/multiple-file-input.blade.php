@props(['disabled' => false])

<label class="custom-file-upload" for="file">
    Images :
    <div class="image-container">
        <div class="carousel">
            <div class="carousel-inner"></div>
        </div>
        <div class="add-icon">+</div>
    </div>
    <input type="file" id="file" name="images[]" {{ $disabled ? 'disabled' : '' }} multiple hidden accept="image/jpeg, image/png, image/jpg, image/gif, image/svg">    
</label>

<script>
    document.getElementById('file').addEventListener('change', function(event) {
        const files = event.target.files;
        console.log(files);
        const carouselInner = document.querySelector('.custom-file-upload .carousel-inner');
        carouselInner.innerHTML = ''; // Clear existing images
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('carousel-item');
                if (index === 0) {
                    img.classList.add('active');
                }
                carouselInner.appendChild(img);
            };
            reader.readAsDataURL(file);
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
