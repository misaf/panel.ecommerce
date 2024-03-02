<div>
    <div class="relative">
        <x-buk-textarea {{ $attributes->merge(['class' => 'dark:bg-gray-700 dark:bg-gradient-to-t dark:from-gray-800 block rounded-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 border-0 border-gray-300 appearance-none dark:text-white dark:border-gray-600 focus:outline-none peer focus:ring-0 resize-none']) }} autocomplete="off" placeholder=" " rows="5" />

        <label class="absolute start-2.5 top-4 z-10 origin-[0] -translate-y-4 transform-gpu text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-yellow-600 rtl:peer-focus:left-auto rtl:peer-focus:-translate-y-4 dark:text-gray-400 peer-focus:dark:text-yellow-500" for="{{ $name }}">{{ __($label) }}</label>
    </div>
</div>
