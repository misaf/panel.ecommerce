<div class="py-4">
    <label class="mb-2 block w-full text-sm text-slate-500" for="{{ $name }}">
        {{ __($label) }}
    </label>

    <x-buk-input :name="$name" {{ $attributes->merge(['class' => 'file:bg-gray-200 file:text-gray-700 file:text-sm file:px-4 file:py-1 file:border-none file:rounded-full shadow-inner w-full rounded-lg border border-gray-200 bg-gray-100 px-5 py-2.5 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40']) }} autocomplete="off" type="file" />
</div>
