@extends('frontend.ipardazfa.layout.app', ['pageTitle' => __(request()->query('sort') ? 'product.' . request()->query('sort') . '_products' : 'frontend.products')])

@section('content')
    <div class="container mx-auto bg-white dark:bg-gray-800">
        <div class="aspect-h-1 aspect-w-3 overflow-hidden lg:mt-8">
            <x-image alt="Houshang-flowers" class="h-full w-full object-cover object-center group-hover:opacity-75" font-size="48" height="400" src="products/photo-1610841803453-1b30e19d2354.jpeg" width="1742"></x-image>
        </div>
    </div>

    <section class="bg-white dark:bg-gray-900">
        <div class="mx-auto mt-8 px-4">
            <hr class="border-2 border-green-800">

            <h1 class="md:text-md mb-8 p-2 text-sm text-green-800 dark:bg-gray-700 lg:text-lg xl:text-xl 2xl:text-2xl">
                <div class="flex justify-between">
                    <div class="flex">
                        {{ __(request()->query('sort') ? 'product.' . request()->query('sort') . '_products' : 'frontend.products') }}
                    </div>

                    <div class="flex">
                        <ul class="inline-flex gap-4 text-sm">
                            <li class="{{ request()->query('sort') === 'expensivest' ? 'bg-green-800 text-white rounded-lg' : '' }} p-2">
                                <a class="inline-flex gap-1" href="{{ route('products.index', ['sort' => 'expensivest']) }}">
                                    <svg class="h-6 w-6" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ __('product.expensivest') }}
                                </a>
                            </li>

                            <li class="{{ request()->query('sort') === 'cheapest' ? 'bg-green-800 text-white rounded-lg' : '' }} p-2">
                                <a class="inline-flex gap-1" href="{{ route('products.index', ['sort' => 'cheapest']) }}">
                                    <svg class="h-6 w-6" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0l-3.75-3.75M17.25 21L21 17.25" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ __('product.cheapest') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </h1>

            <div class="grid grid-cols-1 gap-x-4 gap-y-10 lg:grid-cols-5 xl:grid-cols-5">
                @foreach ($products as $item)
                    <a class="group" href="{{ route('products.show', [$item->token, $item->slug]) }}">
                        <div class="aspect-h-1 aspect-w-1 overflow-hidden rounded-lg">
                            <x-image :alt="$item->name" :src="$item->latestOfProductImage?->image" class="h-full w-full object-cover object-center group-hover:opacity-75" font-size="48" height="300" width="450"></x-image>
                        </div>

                        <div class="md:text-md mt-4 space-y-4 text-sm text-gray-800 ltr:capitalize dark:text-white lg:text-lg xl:text-xl 2xl:text-2xl">
                            <h2>{{ $item->name }}</h2>
                            <p>{{ $item->latestOfProductPrice?->price }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
