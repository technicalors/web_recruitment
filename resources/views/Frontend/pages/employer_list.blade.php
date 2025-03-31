@extends('Frontend.layouts.app')

@section('pageTitle', 'Danh sách công ty')

@push('meta')
    <meta name="description" content="Trang danh sách về công ty">
@endpush

@section('content')
    <main class="main">
        <div class="container">
            <div class="banner-hero banner-single banner-single-bg">
                <div class="block-banner text-center">
                    <h3><span class="color-brand-2">600+ Deal </span> tuyển dụng ngay</h3>
                    <div class="font-sm color-text-paragraph-2 mt-10">Kết nối với nhiều doanh nghiệp toàn quốc</div>
                    <div class="form-find text-start mt-40">
                        <form method="GET" action="{{ route('employers.index') }}">
                            {{-- <select name="industry" class="form-input">
                                <option value="">Ngành nghề</option>
                                @foreach ($industries as $id => $name)
                                    <option value="{{ $id }}" {{ request('industry') == $id ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                            <select name="province" class="form-input">
                                <option value="">Chọn tỉnh/TP</option>
                                @foreach ($provinces as $code => $name)
                                    <option value="{{ $code }}"
                                        {{ request('province') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select> --}}
                            <input name="keyword" type="text" class="form-input" placeholder="Nhập từ khoá..."
                                value="{{ request('search') }}">
                            <button type="submit" class="btn btn-default">Tìm kiếm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <section class="section-box mt-30">
            <div class="container">
                <div class="row flex-row-reverse">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 float-right">
                        <div class="content-page">
                            <div class="box-filters-job">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-5">
                                        <span class="text-small text-showing">Hiển thị
                                            <strong>{{ $employers->firstItem() }}-{{ $employers->lastItem() }} </strong>trên
                                            <strong>{{ $employers->total() }} </strong>công việc</span>
                                    </div>
                                    <div class="col-xl-6 col-lg-7 text-lg-end mt-sm-15">
                                        <div class="display-flex2">
                                            <div class="box-border mr-10">
                                                <span class="text-sortby">Hiển thị:</span>
                                                <div class="dropdown dropdown-sort">
                                                    <button class="btn dropdown-toggle" id="dropdownSort" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        data-bs-display="static">
                                                        <span>{{ request('per_page', 12) }}</span><i
                                                            class="fi-rr-angle-small-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-light"
                                                        aria-labelledby="dropdownSort">
                                                        <li><a class="dropdown-item" href="?per_page=10">10</a></li>
                                                        <li><a class="dropdown-item" href="?per_page=12">12</a></li>
                                                        <li><a class="dropdown-item" href="?per_page=20">20</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="box-border">
                                                <span class="text-sortby">Sắp xếp theo:</span>
                                                <div class="dropdown dropdown-sort">
                                                    <button class="btn dropdown-toggle" id="dropdownSort2" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        data-bs-display="static">
                                                        <span>{{ request('sort', 'newest') == 'newest' ? 'Mới nhất' : (request('sort') == 'oldest' ? 'Cũ nhất' : 'Đánh giá cao') }}</span><i
                                                            class="fi-rr-angle-small-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-light"
                                                        aria-labelledby="dropdownSort2">
                                                        <li><a class="dropdown-item" href="?sort=newest">Newest Post</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="?sort=oldest">Oldest Post</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="?sort=rating">Rating Post</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($employers as $employer)
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                        <div class="card-grid-1 hover-up wow animate__animated animate__fadeIn">
                                            <div class="d-flex justify-content-between align-items-start mt-3">
                                                <div class="text-start">
                                                    <div class="image-box d-inline-block">
                                                        <a href="{{ route('employers.show', $employer->employer_id) }}">
                                                            <img src="{{ asset($employer->logo) }}"
                                                                alt="{{ $employer->company_name }}">
                                                        </a>
                                                    </div>
                                                    <h5 class="font-bold mt-2">
                                                        <a href="company-details">{{ $employer->company_name }}</a>
                                                    </h5>
                                                    <span class="card-location d-block">{{ $employer->address }}</span>
                                                    <div class="mt-2">
                                                        @for ($i = 0; $i < 5; $i++)
                                                            <img src="{{ asset('assets/imgs/template/icons/star.svg') }}"
                                                                alt="rating">
                                                        @endfor
                                                        <span
                                                            class="font-xs color-text-mutted ml-2">({{ $employer->reviews_count }})</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column mt-auto">
                                                    <a class="btn btn-grey-big"
                                                        href="{{ route('jobs.index', ['company' => $employer->id]) }}">
                                                        <span>{{ $employer->jobs_count }} Công việc</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="pagination">
                                {{ $employers->appends(request()->query())->links('Frontend.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
