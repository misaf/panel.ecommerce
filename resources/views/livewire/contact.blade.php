<div>
    <section class="bg-white dark:bg-gray-900 dark:bg-gradient-to-t dark:from-gray-800">
        <div class="container mx-auto py-12">
            <div>
                <h1 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-yellow-400 md:text-3xl">{{ __('از خدمات پشتیبانی ما بهره‌مند شوید') }}</h1>

                <p class="mt-3 text-gray-500 dark:text-gray-400">{{ __('نظرات شما برای ما مهم است. لطفاً فرم زیر را تکمیل کنید یا از طریق ایمیل با ما تماس بگیرید.') }}</p>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-12 lg:grid-cols-2">
                <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                    <x-frontend.oxide.contact.feature :title="__('form.email')" icon="heroicon-m-envelope">
                        <x-slot:description>
                            {{ __('خدمات پشتیبانی ما همیشه در دسترس است.') }}
                        </x-slot:description>

                        <x-slot:more>
                            {{ __('info@iPardazfa.ir') }}
                        </x-slot:more>
                    </x-frontend.oxide.contact.feature>

                    <x-frontend.oxide.contact.feature :title="__('شبکه های اجتماعی')" icon="fab-twitter">
                        <x-slot:description>
                            {{ __('دنبال کن تا از همه چیز باخبر باشی.') }}
                        </x-slot:description>

                        <x-slot:more>
                            <x-frontend.oxide.socials />
                        </x-slot:more>
                    </x-frontend.oxide.contact.feature>

                    <x-frontend.oxide.contact.feature :title="__('گفتگوی زنده')" icon="heroicon-m-chat-bubble-oval-left-ellipsis">
                        <x-slot:description>
                            {{ __('کارشناسان ما می توانند به شما کمک کنند تا یک محصول یا خدمت مناسب انتخاب کنید.') }}
                        </x-slot:description>

                        <x-slot:more>
                            {{ __('لینک گفتگوی زنده') }}
                        </x-slot:more>
                    </x-frontend.oxide.contact.feature>

                    <x-frontend.oxide.contact.feature :title="__('model.faq')" icon="heroicon-m-question-mark-circle">
                        <x-slot:description>
                            {{ __('می توانید سوالات خود را در قسمت پرسش و پاسخ سایت ما مشاهده کنید.') }}
                        </x-slot:description>

                        <x-slot:more>
                            <a href="#">{{ __('لینک پرسش و پاسخ') }}</a>
                        </x-slot:more>
                    </x-frontend.oxide.contact.feature>
                </div>

                <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-800 md:p-6">
                    <form wire:submit="save">
                        <x-honeypot livewire-model="extraFields" />

                        {{ $this->form }}

                        <div class="my-4">
                            <x-filament::button type="submit" size="xl" icon="heroicon-m-paper-airplane">
                                {{ __('button.send_message') }}
                            </x-filament::button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
