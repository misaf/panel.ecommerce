<x-mail::message>
# {{ $newsletter->name }}

@if($posts instanceof \Illuminate\Database\Eloquent\Collection)
    @foreach($posts as $post)
        ## {{ $post->name }}

        {!! $post->description !!}

        @if(!$loop->last)
            ---
        @endif
    @endforeach
@else
    ## {{ $posts->name }}

    {!! $posts->description !!}
@endif

<x-mail::button :url="$unsubscribeSpecificUrl">
{{ __('newsletter::mail.unsubscribe_from_this') }}
</x-mail::button>

<x-mail::button :url="$unsubscribeAllUrl">
{{ __('newsletter::mail.unsubscribe_from_all') }}
</x-mail::button>

{{ __('newsletter::mail.thanks') }},<br>
{{ config('app.name') }}

<x-slot:subcopy>
{{ __('newsletter::mail.subcopy', ['url' => $unsubscribeSpecificUrl]) }}
</x-slot:subcopy>
</x-mail::message>