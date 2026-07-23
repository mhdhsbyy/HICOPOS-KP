<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex w-full justify-center items-center px-6 py-1 bg-black border border-transparent rounded-md font-semibold text-[14px] text-white uppercase tracking-widest hover:bg-[#CD853F] focus:bg-gray-900 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 py-2'
]) }}>
    {{ $slot }}
</button>
