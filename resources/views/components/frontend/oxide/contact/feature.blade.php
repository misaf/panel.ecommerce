<div>
    <span class="inline-block rounded-lg bg-blue-100/80 p-3 text-gray-400 dark:bg-gray-800">
        @svg($icon, 'h-5 w-5')
    </span>

    <h2 class="mt-4 text-base font-medium text-gray-500 dark:text-yellow-400">{{ $title ?? '' }}</h2>

    <p class="mt-6 text-sm leading-6 tracking-wide text-gray-500 dark:text-gray-400">{{ $description ?? '' }}</p>

    <p class="mt-2 text-sm tracking-wide text-gray-500 dark:text-gray-400">{{ $more ?? '' }}</p>
</div>
