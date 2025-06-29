@extends('Frontend.layouts.app')

@section('pageTitle', $employer->company_name . ' - Thông tin công ty & việc làm')

@push('meta')
    <meta name="description"
        content="{{ 'Khám phá thông tin chi tiết về ' . $employer->company_name . ', cơ hội nghề nghiệp và môi trường làm việc chuyên nghiệp.' }}">
@endpush

@section('content')
    <style>
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease, filter 0.4s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        @media (max-width: 600px) {
            .gallery-item img {
                height: 150px;
            }
        }
    </style>

    <main class="main">
        <section class="section-box-2">
            <div class="container">
                <div class="banner-hero banner-image-single">
                    <img src="{{ $employer->background_img
                        ? \App\Helpers\CustomHelper::logoSrc($employer->background_img)
                        : asset('uploads/default_banner.png') }}"
                        alt="{{ $employer->name }}" class="img-fix">
                </div>
                <div class="box-company-profile">
                    <div class="image-compay">
                        <img src="{{ \App\Helpers\CustomHelper::logoSrc($employer->logo) }}" alt="{{ $employer->name }}"
                            class="img-fluid d-block mx-auto bg-white p-1 img-company-fix">
                    </div>
                    <div class="row mt-10">
                        <div class="col-lg-8 col-md-12">
                            <h5 class="f-18">
                                {{ $employer->company_name }}
                            </h5>
                            <p class="mt-3 mb-4 about-body">
                                {{ $employer->about }}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-12 text-lg-end">
                            <a class="btn btn-call-icon btn-apply btn-apply-bigs btn-call-fix"
                                href="{{ route('contact.index') }}">
                                Liên hệ với chúng tôi
                            </a>
                        </div>
                    </div>
                </div>
                <div class="box-nav-tabs mt-3 mt-md-1 mb-5">
                    <ul class="nav nav-fix" role="tablist">
                        <li>
                            <a class="btn btn-border aboutus-icon mr-15 mb-5 active tablist-fix" href="#tab-about"
                                data-bs-toggle="tab" role="tab" aria-controls="tab-about" aria-selected="true">
                                Về chúng tôi
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-border recruitment-icon mr-15 mb-5 tablist-fix" href="#employer_benefit"
                                data-bs-toggle="tab" role="tab" aria-controls="employer_benefit"
                                aria-selected="false">Quyền lợi
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-border people-icon mb-5 tablist-fix" href="#images" data-bs-toggle="tab"
                                role="tab" aria-controls="images" aria-selected="false">
                                Hình ảnh về chúng tôi
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="border-bottom pt-10 pb-10"></div>
            </div>
        </section>

        <section class="section-box mt-0 mt-md-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                        <div class="content-single">
                            <div class="tab-content">
                                <div class="tab-pane fade show active custom-text" id="tab-about" role="tabpanel"
                                    aria-labelledby="tab-about">
                                    <h4 class="custom-h4">Chào mừng đến với {{ $employer->company_name }}</h4>
                                    <p>{!! $employer->company_description !!}</p>
                                </div>
                                <div class="tab-pane fade custom-text" id="employer_benefit" role="tabpanel"
                                    aria-labelledby="employer_benefit">
                                    <h4 class="custom-h4">Quyền lợi</h4>
                                    <p>{!! $employer->employer_benefit !!}</p>
                                </div>
                                <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images">
                                    <h4 class="custom-h4">
                                        Hình ảnh về chúng tôi
                                    </h4>
                                    <div class="gallery-container">
                                        @php
                                            $images = json_decode($employer->images, true);
                                        @endphp

                                        @if (!empty($images) && is_array($images))
                                            @foreach ($images as $image)
                                                <div class="gallery-item">
                                                    <img src="{{ asset($image) }}" alt="Ảnh về chúng tôi">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($latestJobs->count())
                            <div class="box-related-job content-page">
                                <h5 class="mb-30">Công việc mới nhất</h5>

                                <div class="box-list-jobs display-list" id="latest-job-wrapper">
                                    {{-- Danh sách công việc --}}
                                    <div class="row" id="job-list">
                                        @include('Frontend.partials.latest_job_items', [
                                            'latestJobs' => $latestJobs,
                                        ])
                                    </div>

                                    {{-- Phân trang --}}
                                    <div class="paginations" id="pagination">
                                        {{ $latestJobs->links('Frontend.pagination.custom') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-12 pl-40 pl-lg-15 mt-lg-30">
                        <div class="sidebar-border sidebar-border-fix">
                            <div class="sidebar-heading">
                                <div class="avatar-sidebar">
                                    <div class="sidebar-info pl-0">
                                        <span class="sidebar-company fw-semibold" style="line-height: 22px;">
                                            {{ $employer->company_name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if ($employer->map_url)
                                <div class="sidebar-list-job">
                                    <div class="box-map">
                                        {!! \App\Helpers\CustomHelper::safeMapHtml($employer->map_url) !!}
                                    </div>
                                </div>
                            @endif
                            <div class="sidebar-list-job">
                                <ul>
                                    <li>
                                        <div class="sidebar-icon-item">
                                            <i class="fi-rr-briefcase"></i>
                                        </div>
                                        <div class="sidebar-text-info">
                                            <span class="text-description">Lĩnh vực công ty</span>
                                            <strong class="small-heading fw-semibold">{{ $employer->company_type }}
                                            </strong>
                                        </div>
                                    </li>
                                    @if ($employer->show_company_address)
                                        <li>
                                            <div class="sidebar-icon-item">
                                                <i class="fi-rr-marker"></i>
                                            </div>
                                            <div class="sidebar-text-info">
                                                <span class="text-description">Địa chỉ</span>
                                                <strong class="small-heading fw-semibold">{{ $employer->address }}
                                                </strong>
                                            </div>
                                        </li>
                                    @endif
                                    <li>
                                        <div class="sidebar-icon-item"><i class="fi-rr-clock"></i></div>
                                        <div class="sidebar-text-info">
                                            <span class="text-description">
                                                Ngày thành lập
                                            </span>
                                            <strong class="small-heading fw-semibold">
                                                {{ $employer->founded_at ? \Carbon\Carbon::parse($employer->founded_at)->format('d/m/Y') : '' }}
                                            </strong>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            @if ($employer->show_company_address)
                                <div class="sidebar-list-job">
                                    <ul class="ul-disc">
                                        <li>{{ $employer->address }}</li>
                                        <li>SĐT: {{ $employer->contact_phone }}</li>
                                        <li>Email: {{ $employer->email }}</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Phân trang
            $(document).on('click', '.paginations a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (!url || url === '#') return;

                loadJobs(url);
            });

            function loadJobs(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#job-list').html(response.html);
                        $('#pagination').html(response.pagination);

                        //Update URL
                        window.history.pushState({}, '', url);
                    },
                    error: function() {
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                    }
                });
            }
        });
    </script>
@endpush
