@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-[12px] text-black']) }}>
    {{ $value ?? $slot }}
</label>
