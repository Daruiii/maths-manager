@props(['goodAnswers' => 50, 'badAnswers' => 50])

<!-- Circular Progress -->
<div class="relative size-40">
    <svg class="size-full" width="36" height="36" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
      <!-- Background Circle -->
      <circle cx="18" cy="18" r="16" fill="none" class="stroke-current  text-green-600" stroke-width="2"></circle>
      <!-- Good Answers Progress Circle inside a group with rotation -->
      <g class="origin-center -rotate-90 transform">
        <circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-green-600" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ 100 - ($goodAnswers / ($goodAnswers + $badAnswers) * 100) }}"></circle>
      </g>
      <!-- Bad Answers Progress Circle inside a group with rotation -->
      <g class="origin-center -rotate-90 transform">
        <circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-red-600" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ ($goodAnswers / ($goodAnswers + $badAnswers) * 100) }}"></circle>
      </g>
    </svg>
    <!-- Percentage Text -->
    <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2 flex flex-col items-center justify-center">
        <span class="text-center text-2xl font-bold text-gray-800">{{ round($goodAnswers / ($goodAnswers + $badAnswers) * 100) }}%</span>
        <span class="text-center text-sm text-green-600">bonnes réponses</span>
    </div>
</div>
<!-- End Circular Progress -->