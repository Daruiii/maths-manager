@props(['href'])

<button class="flex items-center justify-center m-3">
  <a href="{{ $href }}" class="relative inline-flex items-center justify-center  px-6 py-1 overflow-hidden text-xs bg-[#318CE7] text-white transition duration-300 ease-out border-2 border-white rounded-lg shadow-md group">
    <span class="absolute inset-0 flex items-center justify-center w-full h-full text-white duration-300 -translate-x-full bg-[#318CE7] group-hover:translate-x-0 ease">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
      </svg>
    </span>
    <span class="absolute flex items-center justify-center w-full h-full text-white transition-all duration-300 transform group-hover:translate-x-full ease">{{ __('Envoyer pour correction') }}</span>
    <span class="relative invisible"> {{ $slot }} </span>
  </a>
</button>