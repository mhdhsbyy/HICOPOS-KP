@props(['messages'])

@if ($messages)
    <p {{ $attributes->merge(['class' => 'text-xs text-red-500 mt-1.5']) }}>
        {{ is_array($messages) ? $messages[0] : $messages }}
    </p>
@endif
