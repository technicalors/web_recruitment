@extends('Admin.layouts.master')

@section('pageTitle', 'Chỉnh sửa Cấu Hình')

@section('content')
    @include('Admin.snippets.page_header')

    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Chỉnh sửa Cấu Hình</h5>
                <a href="{{ route('admin.configs.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.configs.update', $config->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="w-25">Key</th>
                                <td>
                                    <input type="text" name="key" class="form-control"
                                        value="{{ old('key', $config->key ?? '') }}" readonly>
                                </td>
                            </tr>

                            @if ($config->key === 'banners')
                                <tr>
                                    <th>Banners</th>
                                    <td>
                                        <div class="mb-3">
                                            <input type="file" name="banners[]" class="file-input" multiple
                                                accept="image/*" id="bannersInput">

                                            @if (!empty($config->value))
                                                @php
                                                    $banners = json_decode($config->value, true);
                                                @endphp

                                                <div class="mt-3">
                                                    <label class="font-weight-bold">Ảnh hiện tại:</label>
                                                    <div class="row">
                                                        @foreach ($banners as $banner)
                                                            <div class="col-md-3 mb-4 banner_img">
                                                                <div class="card shadow-sm border-0">
                                                                    <img src="{{ asset('storage/' . $banner) }}"
                                                                        class="img-fluid rounded" alt="Banner" />
                                                                    <div class="card-body text-center">
                                                                        <a href="#"
                                                                            class="btn btn-danger btn-sm delete-banner"
                                                                            data-url="{{ route('admin.configs.deleteBanner', ['banner' => $banner]) }}">
                                                                            <i class="fas fa-trash-alt"></i> Xóa
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th>Logo</th>
                                    <td>
                                        <div class="mb-3">
                                            <input type="file" name="logo" class="form-control" accept="image/*"
                                                id="logoInput">

                                            @if (!empty($config->value))
                                                <div class="mt-3">
                                                    <label>Logo hiện tại:</label>
                                                    <img src="{{ asset('storage/' . $config->value) }}" class="img-fluid" />
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="text-end" style="margin-top: 15px">
                        <button type="submit" class="btn btn-success">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/uploader_bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/fileinput.min.js') }}"></script>

    <script>
        $(document).on('click', '.delete-banner', function(e) {
            e.preventDefault();

            var bannerUrl = $(this).data('url'); // Lấy URL xóa ảnh từ data-url
            var bannerElement = $(this).closest('.banner_img'); // Phần tử chứa ảnh và nút xóa

            $.ajax({
                url: bannerUrl,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.success) {
                        bannerElement.remove();
                        alert('Ảnh đã được xóa thành công.');
                    } else {
                        alert('Lỗi khi xóa ảnh.');
                    }
                },
                error: function() {
                    alert('Đã xảy ra lỗi khi xóa ảnh.');
                }
            });
        });
    </script>
@endpush
