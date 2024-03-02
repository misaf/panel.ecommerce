<div>
    <section class="bg-white dark:bg-gray-700">
        <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6 lg:py-16">
            <div class="flex gap-8">
                <div class="w-96">
                    <a class="group" href="{{ route('products.show', [$product->token, $product->slug]) }}">
                        <div class="aspect-h-1 aspect-w-1 xl:aspect-h-5 xl:aspect-w-8 w-full overflow-hidden">
                            {{ $product->getFirstMedia()->img()->attributes(['class' => 'h-full w-full origin-top-left rotate-12 object-cover saturate-50 group-hover:opacity-75']) }}
                        </div>
                    </a>
                </div>

                <div class="flex flex-col gap-x-2 space-y-6">
                    <div>
                        {{ $product->productCategory->name }}
                    </div>

                    <form class="max-w-sm mx-auto">
                        <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a number:</label>
                        <input type="number" id="number-input" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="90210" required />
                    </form>

                    <div>
                        {{ number_format($product->productPrice->price->getAmount()->toInt()) }}
                    </div>

                    <div>
                        <x-filament::button icon="heroicon-m-plus" size="xl">
                            {{ __('Add to Cart') }}
                        </x-filament::button>

                        <x-filament::button icon="heroicon-m-plus" size="xl">
                            {{ __('Add to Wishlist') }}
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
