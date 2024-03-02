<div>
    <button class="flex items-center focus:outline-none">
        <svg class="h-6 w-6 flex-shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 12H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>

        <h1 class="mx-4 text-xl text-gray-700 dark:text-yellow-400">{{ $faq->name }}</h1>
    </button>

    <div class="mt-8 flex md:mx-10">
        <span class="border border-blue-500"></span>

        <div class="max-w-3xl px-4 text-white">{!! $faq->description !!}</div>
    </div>

    <hr class="my-8 border-gray-200 dark:border-gray-700">
</div>
