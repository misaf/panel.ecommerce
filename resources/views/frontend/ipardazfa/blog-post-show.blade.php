@extends('frontend.ipardazfa.layout.app', ['pageTitle' => __('بلاگ')])

@section('content')
    <section class="bg-white dark:bg-gray-700">
        <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6 lg:py-16">
            <div class="flex">
                <div class="mx-auto flex justify-between px-4">
                    <article class="format format-sm sm:format-base lg:format-lg format-blue dark:format-invert mx-auto w-full max-w-2xl">
                        <header class="not-format mb-4 lg:mb-6">
                            <h1 class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 dark:text-white lg:mb-6 lg:text-4xl">{{ $blogPost->name }}</h1>
                        </header>
                        <p class="lead">
                            {!! $blogPost->description !!}
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6 lg:py-16">
            <div class="border-b-2 py-8 dark:border-yellow-500 lg:mb-16">
                <h2 class="mb-4 text-3xl font-extrabold tracking-wide text-gray-900 dark:text-white lg:text-4xl">{{ __('سایر مقالات') }}</h2>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                @foreach ($blogPosts as $item)
                    <a class="group" href="">
                        <div class="aspect-h-1 aspect-w-1 xl:aspect-h-5 xl:aspect-w-8 w-full overflow-hidden">
                            <img alt="{{ $item->name }}" class="h-full w-full origin-top-left object-cover saturate-50 group-hover:opacity-75" src="{{ $item->image? route('img', [$item->image, 'w' => 960]): Avatar::create($item->image)->setDimension(72)->setFontSize(32)->toBase64() }}" title="{{ $item->name }}"" title="{{ $item->name }}">
                        </div>

                        <h1 class="text-balance mt-4 text-xl font-bold tracking-wide dark:text-white">{{ $item->name }}</h1>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
