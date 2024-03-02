<div>
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6 lg:py-16">
            <div class="border-b-2 py-8 dark:border-yellow-500 lg:mb-16">
                <h2 class="mb-4 text-3xl font-extrabold tracking-wide text-gray-900 dark:text-white lg:text-4xl">{{ __('model.faq') }}</h2>
            </div>

            <div class="mt-8 lg:-mx-12 lg:flex xl:mt-16">
                <div class="lg:mx-12">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-800 dark:text-white">{{ __('model.faq_category') }}</h1>

                        <div class="mt-4 space-y-4 lg:mt-8">
                            @foreach ($faqCategories as $faqCategory)
                                <livewire:faq.show-faq-category :$faqCategory :key="$faqCategory->id" />
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex-1 lg:mx-12 lg:mt-0">
                    @foreach ($faqs as $faq)
                        <livewire:faq.show-faq :$faq :key="$faq->id" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
