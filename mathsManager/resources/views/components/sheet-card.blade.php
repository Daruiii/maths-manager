@props(['title', 'date', 'number'])

<div class="sheet-card">
    <div class="sheet-bg"></div>
    <div class="sheet-blob"></div>
    <div class="z-10 text-center">
        {{-- loop number here --}}
        <h3 class="text-sm font-small mb-3 px-2">N° {{ $number }}</h3>
        <h3 class="text-sm font-medium mb-3 px-2">{{ $title }}</h3> 
        <div class="mt-2">
            <p class="text-xs text-gray-600">Date: {{ $date }}</p>
        </div>
    </div>
</div>

<style>
    .sheet-card {
        position: relative;
        width: 150px; 
        height: 190px;
        border-radius: 14px;
        z-index: 1111;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 5px 5px 10px #bebebe, -5px -5px 10px #ffffff;
    }

    .sheet-bg {
        position: absolute;
        top: 5px;
        left: 5px;
        width: 140px;
        height: 180px;
        z-index: 2;
        background: rgba(255, 255, 255, .95);
        backdrop-filter: blur(24px);
        border-radius: 10px;
        overflow: hidden;
        outline: 2px solid white;
    }

    .sheet-blob {
        position: absolute;
        z-index: 1;
        top: 50%;
        left: 50%;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #FFA500;
        opacity: 1;
        filter: blur(8px);
        animation: blob-bounce 5s infinite ease;
    }

    @keyframes blob-bounce {
        0% {
            transform: translate(-100%, -100%) translate3d(0, 0, 0);
        }

        25% {
            transform: translate(-100%, -100%) translate3d(100%, 0, 0);
        }

        50% {
            transform: translate(-100%, -100%) translate3d(100%, 100%, 0);
        }

        75% {
            transform: translate(-100%, -100%) translate3d(0, 100%, 0);
        }

        100% {
            transform: translate(-100%, -100%) translate3d(0, 0, 0);
        }
    }
</style>
