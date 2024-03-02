<div class="py-4">
    <label class="mb-2 block w-full text-sm text-slate-500" for="{{ $name }}">
        {{ __($label) }}
    </label>

    <x-buk-textarea {{ $attributes->merge(['class' => 'mt-2 block w-full rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-400 focus:ring-opacity-40 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-600 dark:focus:border-blue-400 resize-none']) }} autocomplete="off" rows="5">{{ $slot }}</x-buk-textarea>
</div>
