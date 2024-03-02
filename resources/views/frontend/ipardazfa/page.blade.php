@extends('frontend.ipardazfa.layout.app', ['pageTitle' => __('تماس با ما')])

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="border-y border-gray-200 bg-gray-50 px-5 py-3 text-gray-700 dark:border-gray-700 dark:bg-gray-700">
        <div class="container mx-auto">
            <ol class="inline-flex items-center space-x-1 rtl:space-x-reverse md:space-x-2">
                <li class="inline-flex items-center">
                    <a class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white" href="{{ route('home') }}">
                        <svg aria-hidden="true" class="me-2.5 h-3 w-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        {{ __('menu.home') }}
                    </a>
                </li>

                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="mx-1 h-3 w-3 text-gray-400 rtl:rotate-180" fill="none" viewBox="0 0 6 10" xmlns="http://www.w3.org/2000/svg">
                            <path d="m1 9 4-4-4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ms-2">{{ __('menu.about_us') }}</span>
                    </div>
                </li>
            </ol>
        </div>
    </nav>

    <section class="bg-white dark:bg-gray-700 dark:bg-gradient-to-t dark:from-gray-800">
        <div class="container mx-auto py-12">
            <div>
                <h1 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-yellow-400 md:text-3xl">{{ __('از خدمات پشتیبانی ما بهره‌مند شوید') }}</h1>

                <p class="mt-3 text-gray-500 dark:text-gray-400">{{ __('نظرات شما برای ما مهم است. لطفاً فرم زیر را تکمیل کنید یا از طریق ایمیل با ما تماس بگیرید.') }}</p>
            </div>

            <div class="mt-10 text-white">
                {!! $page->description !!}
            </div>
        </div>
    </section>
@endsection
