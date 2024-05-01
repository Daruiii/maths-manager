@props(['href'])

<div class="flex items-center justify-center">
  <a href="{{ $href }}" class="px-5 py-2.5 relative rounded-lg group overflow-hidden text-xs md:text-sm bg-white text-purple-600 border-2 border-purple-500 inline-block">
    <span class="absolute top-0 left-0 flex w-full h-0 mb-0 transition-all duration-200 ease-out transform translate-y-0 bg-purple-600 group-hover:h-full opacity-90"></span>
    <span class="relative group-hover:text-white">{{ $slot }}</span>
  </a>
</div>