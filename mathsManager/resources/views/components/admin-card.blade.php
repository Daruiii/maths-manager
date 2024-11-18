@props(['image' => '', 'title' => '', 'href' => '#', 'type' => 1])

<a href="{{ $href }}"
    class="admin-card flex {{ $type === 1 ? 'flex-col' : 'flex-row' }} items-center justify-center text-center p-4 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg hover:scale-105
        {{ $type === 1 ? 'bg-white w-36 h-36' : 'bg-gray-100 w-48 h-24' }}">
    @if ($image)
        <img src="{{ $image }}" alt="{{ $title }}" class="{{ $type === 1 ? 'w-12 h-12 mb-2' : 'w-10 h-10 mr-2' }}">
    @endif
    <span class="{{ $type === 1 ? 'text-sm' : 'text-xs' }} font-semibold">{{ $title }}</span>
</a>

<style>
    .admin-card {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .admin-card.type-1 {
        width: 150px;
        height: 150px;
    }

    .admin-card.type-2 {
        width: 180px;
        height: 80px;
        flex-direction: row;
    }
</style>
