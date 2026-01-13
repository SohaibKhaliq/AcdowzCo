@foreach($product->video as $video)
    @continue(! $video['url'])

    <div class="video-thumbnail">
        <img src="{{ $video['thumbnail'] }}" alt="{{ $product->name }}">
        <x-core::icon name="ti ti-player-play-filled" />
    </div>
@endforeach
