<div>
    <section class="bg-5 dark:bg-gray-800 lg:bg-[url('https://coin-payment.test/templates/coin-payment/header.png')] lg:bg-contain lg:bg-no-repeat lg:ltr:bg-right lg:rtl:bg-left">
        <div class="mx-auto max-w-screen-xl place-content-start px-4 py-8 lg:py-48">
            <h1 class="mb-4 max-w-3xl text-4xl font-extrabold leading-snug dark:text-white lg:text-5xl">{{ __('هدیه‌ای که لذت خرید را دوچندان می‌کند') }}</h1>
            <p class="mb-6 max-w-2xl font-light leading-loose text-gray-500 dark:text-gray-400 md:text-lg lg:mb-8 lg:text-xl">{{ __('گیفت کارت‌ها هدیه‌ای عالی هستند که می‌توانند لذت خرید را دوچندان کنند. آن‌ها گزینه‌ای مقرون به صرفه و انعطاف‌پذیر هستند که به گیرنده اجازه می‌دهد تا هدیه مورد نظر خود را انتخاب کند') }}</p>

            <div class="flex gap-x-4">
                <x-filament::button icon="heroicon-m-plus" size="xl">
                    {{ __('خرید گیفت کارت') }}
                </x-filament::button>

                <x-filament::button outlined size="xl">
                    {{ __('مشاوره') }}
                </x-filament::button>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="bg-white dark:bg-5">
        <div class="mx-auto max-w-screen-xl px-4 py-8 text-center lg:px-6 lg:py-16">
            <dl class="mx-auto grid max-w-screen-md gap-8 text-gray-900 dark:text-white sm:grid-cols-3">
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl font-black oldstyle-nums dark:text-primary md:text-4xl">73M+</dt>
                    <dd class="text-xl font-black text-gray-900 dark:text-primary">developers</dd>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-3xl font-black oldstyle-nums dark:text-primary md:text-4xl">1B+</dt>
                    <dd class="text-lg font-black text-gray-900 dark:text-primary">contributors</dd>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <dt class="mb-2 text-lg font-black oldstyle-nums dark:text-primary md:text-4xl">500 نفر</dt>
                    <dd class="text-2xl font-black text-gray-900 dark:text-primary">{{ __('تعداد مشتریان') }}</dd>
                </div>
            </dl>
        </div>
    </section>
    <!-- End Stats -->

    <!-- Feature Lists -->
    <section class="bg-white dark:bg-5">
        <div class="mx-auto max-w-screen-xl px-4 py-8 sm:py-16 lg:px-6">
            <div class="space-y-8 md:grid md:grid-cols-2 md:gap-12 md:space-y-0 lg:grid-cols-3">
                <div class="p-4">
                    @svg('fas-gift', 'dark:text-yellow-400 mb-4 h-14 w-14')

                    <h3 class="mb-2 text-xl font-bold tracking-wide dark:text-yellow-400">{{ __('گزینه‌ای عالی برای هدیه دادن') }}</h3>
                    <p class="text-wrap leading-loose tracking-wide text-gray-500 dark:text-white">{{ __('گیفت کارت‌ها انتخابی عالی برای هدیه دادن هستند زیرا به گیرنده اجازه می‌دهند تا هدیه مورد نظر خود را انتخاب کند.') }}</p>
                </div>

                <div class="p-4">
                    @svg('fas-business-time', 'dark:text-yellow-400 mb-4 h-14 w-14')

                    <h3 class="mb-2 text-xl font-bold tracking-wide dark:text-yellow-400">{{ __('مقرون به صرفه') }}</h3>
                    <p class="text-wrap leading-loose tracking-wide text-gray-500 dark:text-white">{{ __('گیفت کارت‌ها می‌توانند یک راه مقرون به صرفه برای خرید هدیه باشند.') }}</p>
                </div>

                <div class="p-4">
                    @svg('fas-cat', 'dark:text-yellow-400 mb-4 h-14 w-14')

                    <h3 class="mb-2 text-xl font-bold tracking-wide dark:text-yellow-400">{{ __('انعطاف‌پذیر') }}</h3>
                    <p class="text-wrap leading-loose tracking-wide text-gray-500 dark:text-white">{{ __('گیفت کارت‌ها انعطاف‌پذیر هستند و می‌توان آن‌ها را در طیف وسیعی از فروشگاه‌ها و شرکت‌ها استفاده کرد.') }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Feature Lists -->

    <!-- Product -->
    @if (isset($products))
        <section class="bg-white dark:bg-5">
            <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6 lg:py-8">
                <div class="mx-auto max-w-screen-md border-b-2 py-8 text-center dark:border-yellow-500 lg:mb-16 lg:max-w-screen-lg">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-wide text-gray-900 dark:text-white lg:text-4xl">{{ __('گیفت کارت') }}</h2>
                    <p class="font-light tracking-wide text-gray-500 dark:text-gray-400 sm:text-xl">{{ __('گیفت کارت هدیه‌ای کاربردی و ماندگار است که می‌تواند برای عزیزان شما لذت‌بخش باشد.') }}</p>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    @foreach ($products as $item)
                        <a class="group" href="{{ route('products.show', [$item->token, $item->slug]) }}">
                            <div class="aspect-h-1 aspect-w-1 xl:aspect-h-5 xl:aspect-w-8 w-full overflow-hidden">
                                {{ $item->getFirstMedia()->img()->attributes(['class' => 'h-full w-full origin-top-left rotate-12 object-cover saturate-50 group-hover:opacity-75']) }}
                            </div>
                            <h3 class="mt-4 text-sm tracking-wide text-gray-900 dark:text-white">{{ $item->name }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- End Product -->

    <!-- Our Company -->
    <section class="bg-white dark:bg-5">
        <div class="mx-auto max-w-screen-xl items-center gap-16 px-4 py-8 lg:grid lg:grid-cols-2 lg:px-6 lg:py-16">
            <div class="font-light leading-relaxed text-gray-500 dark:text-gray-400 sm:text-lg">
                <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ __('ما طراحان و توسعه دهندگان هستیم نوآوران و حل کننده مشکلات') }}</h2>
                <p class="mb-4">{{ __('ما یک تیم متشکل از افراد متخصص و با تجربه هستیم که در زمینه استراتژی، طراحی و توسعه تخصص داریم. ما عاشق آن هستیم که راه حل های نوآورانه ایجاد کنیم و مشکلات را حل کنیم. ما به دنبال بهترین راه حل ها برای مشتریان خود هستیم و همیشه به دنبال بهبود کیفیت کار خود هستیم. ما یک تیم با استعداد و متعهد هستیم که به مشتریان و کار خود افتخار می کنیم.') }}</p>
                <p>{{ __('به عنوان یک تیم استراتژیست، طراح و توسعه دهنده، ما توانایی حل هر گونه چالش را داریم. ما می توانیم راه حل های نوآورانه ای ایجاد کنیم که نیازهای مشتریان ما را برآورده کند. ما همچنین می توانیم این راه حل ها را به واقعیت تبدیل کنیم و محصولات زیبا و کاربردی بسازیم. ما یک تیم هستیم که می تواند در دنیای واقعی تفاوت ایجاد کند.') }}</p>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-4">
                <img alt="" class="w-full rounded-lg saturate-50" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/content/office-long-2.png">
                <img alt="" class="mt-4 w-full rounded-lg saturate-50 lg:mt-10" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/content/office-long-1.png">
            </div>
        </div>
    </section>
    <!-- End Our Company -->

    <!-- From Blog -->
    @if (isset($blogPosts))
        <section class="bg-white dark:bg-5">
            <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6 lg:py-16">
                <div class="mx-auto max-w-screen-md border-b-2 py-8 text-center dark:border-yellow-500 lg:mb-16 lg:max-w-screen-lg">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-wide text-gray-900 dark:text-white lg:text-4xl">{{ __('وبلاگ') }}</h2>
                    <p class="text-xl font-light tracking-wide text-gray-500 dark:text-gray-400 sm:text-xl">{{ __('در این مطالب نکات و ترفندهای مفیدی نیز برای استفاده از گیفت کارت ها ارائه شده است.') }}</p>
                </div>

                <div class="lg:-mx-6 lg:flex">
                    <div class="lg:w-3/4 lg:px-6">
                        <a href="{{ route('blogs.posts.show', $blogPosts->first()->slug) }}">
                            {{ $item->getFirstMedia()->img()->attributes(['class' => 'h-80 w-full rounded-xl object-cover object-center saturate-50 xl:h-[28rem]']) }}

                            <p class="mt-6 text-gray-900 ltr:uppercase dark:text-yellow-400">{{ $blogPosts->first()->blogPostCategory->name }}</p>

                            <h1 class="mt-4 text-2xl font-semibold leading-tight text-gray-800 dark:text-white">{{ $blogPosts->first()->name }}</h1>
                        </a>
                    </div>

                    <div class="mt-8 lg:mt-0 lg:w-1/4 lg:px-6">
                        @foreach ($blogPosts->skip(1) as $item)
                            <div>
                                <h3 class="text-gray-900 ltr:uppercase dark:text-yellow-400">{{ $item->blogPostCategory->name }}</h3>

                                <a class="mt-2 block font-medium text-gray-700 hover:text-gray-500 hover:underline dark:text-gray-400" href="{{ route('blogs.posts.show', $item->slug) }}">{{ $item->name }}</a>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-gray-700">
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- End From Blog -->
</div>
