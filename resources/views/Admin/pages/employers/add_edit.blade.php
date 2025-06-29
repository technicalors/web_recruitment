@extends('Admin.layouts.master')

@section('pageTitle', isset($employer) ? 'Chỉnh sửa nhà tuyển dụng' : 'Thêm nhà tuyển dụng')

@section('content')
    @include('Admin.snippets.page_header')

    <div class="content">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ isset($employer) ? 'Chỉnh sửa nhà tuyển dụng' : 'Thêm nhà tuyển dụng' }}</h5>
            </div>

            <div class="card-body">
                <form
                    action="{{ isset($employer) ? route('admin.employers.update', $employer->employer_id) : route('admin.employers.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($employer))
                        @method('PUT')
                    @endif

                    {{-- <div class="mb-3">
                        <label class="form-label">Người dùng</label>
                        <select name="user_id" class="form-control select2" required>
                            <option value="">Chọn người dùng</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ isset($employer) && $employer->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên nhà tuyển dụng <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control text-to-slug"
                                value="{{ old('company_name', $employer->company_name ?? '') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control text-to-slug"
                                value="{{ old('slug', $employer->slug ?? '') }}" readonly>
                        </div>
                    </div>

                    <div class="file-input-wrapper mb-3">
                        <label class="form-label">Logo</label>
                        <input type="hidden" name="logo" class="all-images"
                            value="{{ isset($employer) && $employer->logo ? $employer->logo : '' }}">

                        <!-- Vẫn giữ input file nhưng dùng để trigger popup -->
                        <input type="file" class="file-input-preview" data-browse-on-zone-click="true">
                    </div>

                    <div class="file-input-wrapper mb-3">
                        <label class="form-label">Ảnh nền</label>
                        <input type="hidden" name="background_img" class="all-images"
                            value="{{ isset($employer) && $employer->background_img ? $employer->background_img : '' }}">

                        <input type="file" class="file-input-preview" data-browse-on-zone-click="true">
                    </div>

                    <div class="file-input-wrapper-array mb-3">
                        <label class="form-label">Ảnh về công ty</label>

                        @if (!isset($employer))
                            {{-- Khi create, images là mảng JSON --}}
                            <input type="hidden" name="images" class="all-images-array"
                                value='@json($imageUrls ?? [])'>
                        @else
                            {{-- Khi edit --}}
                            <input type="hidden" name="images" class="all-images-array" value="{{ $employer->images }}">
                        @endif

                        <input type="file" class="file-input-preview-array" data-browse-on-zone-click="true">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả nhà tuyển dụng</label>
                        <textarea name="company_description" class="form-control ckeditor">
                            {{ old('company_description', $employer->company_description ?? '') }}
                        </textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quyền lợi</label>
                        <textarea name="employer_benefit" class="form-control ckeditor">
                            {{ old('employer_benefit', $employer->employer_benefit ?? '') }}
                        </textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control"
                                value="{{ old('website', $employer->website ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="contact_phone" class="form-control" maxlength="11"
                                value="{{ old('contact_phone', $employer->contact_phone ?? '') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Công ty nổi bật</label>
                        <div style="display:flex;align-items: center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_hot" id="is_hot_yes" value="yes"
                                    {{ old('is_hot', $employer->is_hot ?? '') === 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_hot_yes">
                                    Có nổi bật
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="radio" name="is_hot" id="is_hot_no" value="no"
                                    {{ old('is_hot', $employer->is_hot ?? '') === 'no' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_hot_no">
                                    Không nổi bật
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">Chọn "Có" nếu bạn muốn làm nổi bật công ty tuyển dụng này.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $employer->address ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hiển thị thông tin công ty</label>
                        <div style="display:flex;align-items: center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="show_company_address"
                                    id="show_company_address_yes" value="1"
                                    {{ old('show_company_address', $employer->show_company_address ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_company_address_yes">
                                    Hiển thị
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="radio" name="show_company_address"
                                    id="show_company_address_no" value="0"
                                    {{ old('show_company_address', $employer->show_company_address ?? 0) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_company_address_no">
                                    Không hiển thị
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $employer->email ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày thành lập <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ph-calendar"></i></span>
                            <input type="text" name="founded_at" class="form-control datepicker-autohide"
                                value="{{ old('founded_at', $employer->founded_at ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Về chúng tôi</label>
                            <input type="text" name="about" class="form-control"
                                value="{{ old('about', $employer->about ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lĩnh vực công ty</label>
                            <input type="text" name="company_type" class="form-control"
                                value="{{ old('company_type', $employer->company_type ?? '') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="map_url" class="form-label">Địa chỉ bản đồ</label>
                        <input type="text" class="form-control" id="map_url" name="map_url"
                            placeholder='<iframe src="https://www.google.com/maps/embed?..."'
                            value="{{ old('map_url', $employer->map_url ?? '') }}">
                        <small class="form-text text-muted">
                            Dán mã nhúng Google Maps (iframe) vào đây để hiển thị bản đồ.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-success">
                        {{ isset($employer) ? 'Cập nhật nhà tuyển dụng' : 'Thêm nhà tuyển dụng' }}
                    </button>
                    <a href="{{ route('admin.employers.index') }}" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('Admin.snippets.ckeditor_file_management')

    <script>
        $(document).ready(function() {
            $('.file-input-wrapper-array').each(function() {
                const $wrapper = $(this);
                const $previewInput = $wrapper.find('.file-input-preview-array');
                const $hiddenInput = $wrapper.find('.all-images-array');

                console.log($hiddenInput.val());
                let allImages = JSON.parse($hiddenInput.val() || '[]');
                console.log(allImages);

                // khởi tạo lại fileinput preview
                function renderPreviews(images) {
                    $previewInput.fileinput('destroy'); // Xoá instance cũ

                    const baseUrl = "{{ asset('') }}";
                    // full URL để preview
                    const previewImages = images.map(image => baseUrl + image);

                    $previewInput.fileinput({
                        initialPreview: previewImages,
                        initialPreviewConfig: images.map(image => ({
                            caption: image.split('/').pop(),
                            key: image,
                            downloadUrl: baseUrl + image
                        })),
                        showRemove: false,
                        showUpload: false,
                        showCancel: false,
                        showClose: false,
                        showZoom: true,
                        showBrowse: false,
                        initialPreviewAsData: true,
                        overwriteInitial: false,
                        browseOnZoneClick: true,
                        dropZoneEnabled: false,
                        showBrowse: true,
                        browseLabel: 'Chọn ảnh',
                        allowedPreviewTypes: ['image'],
                        fileActionSettings: {
                            showRemove: true,
                            removeIcon: '<i class="ph-x text-danger"></i>',
                            removeClass: 'btn btn-sm btn-light-danger rounded-pill',
                            showDownload: false,
                            showBrowse: false,
                            showZoom: false,
                        }
                    });
                }

                renderPreviews(allImages);

                // ❌ Ngăn người dùng chọn ảnh từ máy
                $previewInput.on('click', function(e) {
                    e.preventDefault();
                });

                // ✅ Mở popup Laravel Filemanager khi click Browse
                $wrapper.on('click', '.btn-file input[type=file]', function(e) {
                    e.preventDefault();

                    window.open('/admin/laravel-filemanager?type=image', 'lfm',
                        'width=900,height=600');

                    window.SetUrl = function(files) {
                        const items = Array.isArray(files) ? files : [files];
                        const baseUrl = "{{ asset('') }}";

                        items.forEach(file => {
                            const url = file.url;

                            // Tạo đường dẫn tương đối từ URL trả về
                            const relativeUrl = new URL(url).pathname.replace(
                                /^\/storage\//, 'storage/');

                            const fullUrl = baseUrl + relativeUrl;

                            // Kiểm tra xem ảnh đã có trong allImages chưa, nếu chưa thì thêm vào
                            if (!allImages.includes(relativeUrl)) {
                                allImages.push(relativeUrl);
                            }
                        });

                        // Cập nhật input hidden với relativeUrl và trigger sự kiện change
                        $hiddenInput.val(JSON.stringify(allImages)).trigger('change');
                    };

                });

                // ✅ Khi xoá ảnh từ preview
                $wrapper.on('click', '.kv-file-remove', function(e) {
                    e.preventDefault();
                    const $frame = $(this).closest('.file-preview-frame');
                    const src = $frame.find('img').attr('src');

                    // Loại bỏ ảnh khỏi allImages bằng relativeUrl
                    allImages = allImages.filter(url => url !== src.replace("{{ asset('') }}",
                        ""));
                    $hiddenInput.val(JSON.stringify(allImages)).trigger('change');
                    $frame.remove();
                });

                $hiddenInput.on('change', function() {
                    const updated = JSON.parse($hiddenInput.val() || '[]');
                    allImages = updated;
                    renderPreviews(allImages);
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // bỏ dấu tiếng Việt
            function removeVietnameseTones(str) {
                str = str.normalize('NFD');
                str = str.replace(/[\u0300-\u036f]/g, "");
                str = str.replace(/đ/g, "d");
                str = str.replace(/Đ/g, "D");
                str = str.replace(/([^0-9a-z-\s])/g, '');
                return str;
            }

            // tạo slug từ tên
            function slugify(text) {
                text = text.toLowerCase();
                text = removeVietnameseTones(text);
                return text.toString()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            }

            $('.text-to-slug[name="company_name"]').on('input', function() {
                var name = $(this).val();
                var slug = slugify(name);
                $('.text-to-slug[name="slug"]').val(slug);
            });

            @if (isset($employer))
                var initialName = $('.text-to-slug[name="company_name"]').val();
                if (initialName) {
                    var initialSlug = slugify(initialName);
                    $('.text-to-slug[name="slug"]').val(initialSlug);
                }
            @endif
        });
    </script>
@endpush
