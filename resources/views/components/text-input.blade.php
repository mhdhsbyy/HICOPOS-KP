@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 h-[30px] focus:border-black focus:ring-[#CD853F] rounded-md shadow-sm']) }}>
