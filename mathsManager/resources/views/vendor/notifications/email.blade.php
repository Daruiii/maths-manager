<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Oups !')
@else
# @lang('Bonjour !')
@endif
@endif

{{-- Intro Lines --}}
{{-- my text --}}
@lang("Vous recevez cet email afin de vérifier votre adresse email. Cliquez sur le bouton ci-dessous pour vérifier votre adresse email.")

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
Verifiez votre adresse email
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@lang("Si vous n'avez pas créé de compte, vous pouvez ignorer cet email.")

{{-- Salutation --}}
@lang('Cordialement'),<br>{{ config('app.name') }}

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "Si vous avez des problèmes pour cliquer sur le bouton 'Verifiez votre adresse email', copiez et collez l'URL suivante\n".
    'dans votre navigateur web :',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
