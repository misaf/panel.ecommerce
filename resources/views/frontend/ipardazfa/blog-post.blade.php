@extends('frontend.ipardazfa.layout.app', ['pageTitle' => __('بلاگ')])

@section('content')
    <div class="flex flex-col justify-center gap-x-4 space-y-4 lg:mt-8 lg:flex-row lg:space-y-0">
        <div class="aspect-h-1 aspect-w-2 w-full overflow-hidden rounded-lg lg:w-2/4">
            <a class="group" href="{{ route('blogs.posts.show', $blogPosts->first()->slug) }}">
                <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg">
                    <x-image :alt="$blogPosts->first()->name" :src="$blogPosts->first()->image" class="h-full w-full object-cover object-center group-hover:opacity-75" font-size="48" height="600" width="600"></x-image>
                </div>

                <div class="absolute bottom-0 w-full">
                    <div class="bg-white/60 py-3 backdrop-blur-sm dark:bg-gray-800/60">
                        <h2 class="lg:text-md text-center text-sm text-gray-800 ltr:capitalize dark:text-white md:text-sm xl:text-lg 2xl:text-xl">
                            {{ $blogPosts->first()->name }}
                        </h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid w-full grid-cols-1 grid-rows-1 gap-4 lg:w-2/4 lg:grid-cols-2 lg:grid-rows-2">
            @foreach ($blogPosts->skip(1) as $item)
                <div class="relative">
                    <a class="group" href="{{ route('blogs.posts.show', $item->slug) }}">
                        <div class="aspect-h-1 aspect-w-2 lg:aspect-w-1 w-full overflow-hidden rounded-lg">
                            <x-image :alt="$item->name" :src="$item->image" class="h-full w-full object-cover object-center group-hover:opacity-75" font-size="48" height="300" width="300"></x-image>
                        </div>

                        <div class="absolute bottom-0 w-full">
                            <div class="bg-white/60 py-3 backdrop-blur-sm dark:bg-gray-800/60">
                                <h2 class="lg:text-md text-center text-sm text-gray-800 ltr:capitalize dark:text-white md:text-sm xl:text-lg 2xl:text-xl">
                                    {{ $item->name }}
                                </h2>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <section class="bg-white dark:bg-gray-900">
        <div class="mx-auto mt-8 space-y-4 px-4 lg:space-y-0">
            <div class="flex flex-col gap-x-10 lg:flex-row">
                <div class="w-full lg:w-3/4">
                    <hr class="border-2 border-green-800">

                    <h1 class="md:text-md mb-8 p-2 text-sm text-green-800 dark:bg-gray-700 lg:text-lg xl:text-xl 2xl:text-2xl">
                        {{ __('جدیدترین مطالب') }}
                    </h1>

                    <div class="grid grid-cols-1 gap-x-4 gap-y-10">
                        @foreach (\App\Models\Blog\BlogPost::with('blogPostCategory')->where('status', 'Enable')->get() as $item)
                            <a class="group" href="{{ route('blogs.posts.show', $item->slug) }}">
                                <div class="flex rounded-lg border shadow">
                                    <div class="aspect-h-1 aspect-w-6 w-1/4 overflow-hidden rounded-r-lg">
                                        <x-image :alt="$item->name" :src="$item->image" class="h-full w-full object-cover object-center group-hover:opacity-75" font-size="48" height="183" width="274"></x-image>
                                    </div>

                                    <div class="w-3/4 space-y-2 px-2 pb-5">
                                        <p class="text-xs text-green-800 dark:bg-gray-700 lg:text-lg">
                                            {{ $item->blogPostCategory->name }}
                                        </p>

                                        <p class="w-full text-xs text-green-800 dark:bg-gray-700 lg:text-lg">
                                            {{ $item->created_at->format('Y-m-d') }}
                                        </p>

                                        <p class="lg:text-md md:text-md text-sm leading-relaxed text-gray-800 ltr:capitalize dark:text-white xl:text-xl 2xl:text-2xl">
                                            {{ $item->name }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 w-full lg:mt-0 lg:w-1/4">
                    <div>
                        <hr class="border-2 border-green-800">

                        <h1 class="md:text-md mb-8 p-2 text-sm text-green-800 dark:bg-gray-700 lg:text-lg xl:text-xl 2xl:text-2xl">
                            {{ __('پربازدیدترین مطالب') }}
                        </h1>

                        @foreach (\App\Models\Blog\BlogPost::with('blogPostCategory', 'user')->where('status', 'Enable')->skip(1)->take(5)->get() as $item)
                            <div class="group">
                                <a class="lg:text-md md:text-md text-sm text-gray-800 group-hover:bg-green-800 group-hover:text-white ltr:capitalize dark:text-white xl:text-xl 2xl:text-2xl" href="{{ route('blogs.posts.show', $item->slug) }}">
                                    <div class="flex flex-col space-y-2 px-2 pb-5 group-hover:rounded-lg group-hover:bg-green-800 group-hover:text-white">
                                        <p class="text-xs text-green-800 group-hover:text-white dark:bg-gray-700 lg:text-lg">
                                            {{ $item->blogPostCategory->name }}
                                        </p>

                                        <p class="w-full text-xs text-green-800 group-hover:text-white dark:bg-gray-700 lg:text-lg">
                                            {{ $item->created_at->format('Y-m-d') }}
                                        </p>

                                        <p class="leading-relaxed">
                                            {{ $item->name }}
                                        </p>
                                    </div>
                                </a>

                                <hr class="border-1 my-6 border-gray-200 dark:border-gray-700">
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <div class="relative">
                            <img class="rounded-lg" src="https://images.unsplash.com/photo-1567696153798-9111f9cd3d0d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Zmxvd2VyJTIwYm91cXVldHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60">

                            <div class="absolute bottom-0 mx-auto w-full p-4 text-center leading-loose" dir="ltr">{{ __('در شبکه های اجتماعی ما را دنبال کنید') }}<br>@golehoushang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
