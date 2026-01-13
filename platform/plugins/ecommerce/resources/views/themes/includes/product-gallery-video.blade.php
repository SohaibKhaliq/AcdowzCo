@if (! empty($product->video))
    @foreach($product->video as $video)
        @continue(! $video['url'])

        <div class="bb-product-video" style="position: relative; width: 100%; background: #000; border-radius: 8px; overflow: hidden;">
            @switch($video['provider'])
                @case('video')
                    @php
                        $fileExtension = File::extension($video['url']);

                        if (! $fileExtension || $fileExtension === 'mov') {
                            $fileExtension = 'mp4';
                        }
                    @endphp

                    <video
                        id="{{ md5($video['url']) }}"
                        playsinline="playsinline"
                        muted
                        preload="auto"
                        class="media-video"
                        aria-label="{{ $product->name }}"
                        poster="{{ $video['thumbnail'] }}"
                        style="width: 100%; height: 100%; object-fit: contain; display: block;"
                    >
                        <source src="{{ $video['url'] }}" type="video/{{ $fileExtension }}">
                        <img src="{{ $video['thumbnail'] }}" alt="{{ $video['url'] }}">
                    </video>
                    <button class="bb-button-trigger-play-video" data-target="{{ md5($video['url']) }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80px; height: 80px; background: rgba(255, 255, 255, 0.9); border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);">
                        <x-core::icon name="ti ti-player-play-filled" style="width: 32px; height: 32px; margin-left: 4px;" />
                    </button>
                    @break
                    
                @case('youtube')
                @case('vimeo')
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000; border-radius: 8px;">
                        <iframe
                            data-provider="{{ $video['provider'] }}"
                            src="{{ $video['url'] }}"
                            allowfullscreen
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            title="{{ $product->name }} Video"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                        ></iframe>
                    </div>
                    @break
                    
                @case('tiktok')
                    <div style="display: flex; justify-content: center; align-items: center; min-height: 400px; background: #f8f9fa; border-radius: 8px; padding: 20px;">
                        <blockquote
                            class="tiktok-embed"
                            cite="{{ $video['url'] }}"
                            data-video-id="{{ $video['video_id'] ?? '' }}"
                            style="max-width: 605px; min-width: 325px; border: none !important;">
                            <section></section>
                        </blockquote>
                    </div>
                    @break
                    
                @case('twitter')
                    <div style="display: flex; justify-content: center; align-items: center; min-height: 400px; background: #f8f9fa; border-radius: 8px; padding: 20px;">
                        <blockquote class="twitter-tweet" style="border: none !important;">
                            <a href="{{ $video['url'] }}"></a>
                        </blockquote>
                    </div>
                    @break
                    
                @default
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000; border-radius: 8px;">
                        <iframe
                            data-provider="{{ $video['provider'] }}"
                            src="{{ $video['url'] }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;">
                        </iframe>
                    </div>
            @endswitch
        </div>
    @endforeach
    
    @if(in_array('tiktok', array_column($product->video, 'provider')))
        <script async src="https://www.tiktok.com/embed.js"></script>
    @endif
    
    @if(in_array('twitter', array_column($product->video, 'provider')))
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    @endif
@endif
