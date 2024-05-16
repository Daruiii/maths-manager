{{-- <x-progress-bar currentQuestion="$currentQuestion" totalQuestions="count($questions)" /> --}}
@props(['currentQuestion', 'totalQuestions'])

<!-- Step Progress -->
<div class="mt-5 w-full flex justify-center">
    <div class="flex items-center gap-x-1 w-1/2">
        @php
            $currentQuestion = intval($currentQuestion);
            $totalQuestions = intval($totalQuestions);
        @endphp
        @for ($i = 1; $i <= $totalQuestions; $i++)
            <div class="flex-grow h-2 rounded flex flex-col justify-center overflow-hidden {{ $i <= $currentQuestion ? 'bg-red-500' : 'bg-red-100' }} text-xs text-white text-center whitespace-nowrap transition duration-500" 
            role="progressbar" aria-valuenow="{{ $i <= $currentQuestion ? 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
        @endfor
        <div>
          <div class="w-10 text-end">
            <span class="text-sm text-red-500">{{ $currentQuestion }} / {{ $totalQuestions }}</span>
          </div>
        </div>
    </div>
</div>
<!-- End Step Progress -->