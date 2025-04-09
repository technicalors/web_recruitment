<section class="section-box" style="display: block; line-height: 0;">
    @php
        $banners = json_decode(\App\Models\Config::where('key', 'banners')->value('value') ?? '[]', true);
    @endphp

    <div class="banner-main banner-slider" style="position: relative;">
        <a href="{{ route('index') }}" style="position: absolute; top: 20px; left: 50px; z-index: 100;">
            <img alt="logo" src="{{ asset(\App\Models\Config::getLogo()) }}" style="height: 60px; width: auto;">
        </a>

        <div class="swiper-wrapper">
            @forelse ($banners as $banner)
                <div class="swiper-slide banner-slide">
                    <img src="{{ \App\Helpers\CustomHelper::logoSrc($banner) }}" alt="Banner"
                        style="height:150px; width: 100%; object-fit: cover;">
                </div>
            @empty
                <div class="swiper-slide banner-slide">
                    <img src="{{ asset('uploads/banner_thumb01.jpg') }}" alt="Banner"
                        style="height:150px; width: 100%; object-fit: cover;">
                </div>
            @endforelse
        </div>

    </div>
</section>
