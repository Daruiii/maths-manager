@props(['href'])

<div class="flex items-center justify-center mb-2">
    <a href="{{ $href }}"
    class="relative inline-flex items-center px-12 py-3 overflow-hidden text-xs bg-[#019875] text-white border-2 border-white rounded-lg hover:text-white group hover:bg-gray-50">
      <span class="absolute left-0 block w-full h-0 transition-all bg-[#175641] opacity-100 group-hover:h-full top-1/2 group-hover:top-0 duration-400 ease"></span>
      <span class="absolute right-0 flex items-center justify-start w-10 h-10 duration-300 transform translate-x-full group-hover:translate-x-0 ease">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
        </svg>
      </span>
      <span class="relative">{{ $slot }}</span>
    </a>
  </div>