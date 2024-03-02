@extends('frontend.ipardazfa.layout.app', ['pageTitle' => __('model.faq')])

@section('content')
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6 lg:py-16">
            <div class="border-b-2 py-8 dark:border-yellow-500 lg:mb-16">
                <h2 class="mb-4 text-3xl font-extrabold tracking-wide text-gray-900 dark:text-white lg:text-4xl">{{ __('model.faq') }}</h2>
            </div>

            <div class="mt-8 lg:-mx-12 lg:flex xl:mt-16">
                <div class="lg:mx-12">
                    <x-frontend.oxide.faq.category.index :$faqCategories />
                </div>

                <div class="mt-8 flex-1 lg:mx-12 lg:mt-0">
                    <x-frontend.oxide.faq.index :$faqs />
                </div>
            </div>
        </div>
    </section>
@endsection
