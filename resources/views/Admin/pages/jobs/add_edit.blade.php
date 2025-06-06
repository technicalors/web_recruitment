@extends('Admin.layouts.master')

@section('pageTitle', isset($job) ? 'Chỉnh sửa công việc' : 'Thêm công việc')

@section('content')
    @include('Admin.snippets.page_header')

    <div class="content">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ isset($job) ? 'Chỉnh sửa công việc' : 'Thêm công việc' }}</h5>
            </div>

            <div class="card-body">
                <form action="{{ isset($job) ? route('admin.jobs.update', $job->job_id) : route('admin.jobs.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($job))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tiêu đề công việc <span class="text-danger">*</span></label>
                            <input type="text" name="job_title" class="form-control text-to-slug"
                                value="{{ old('job_title', $job->job_title ?? '') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control text-to-slug"
                                value="{{ old('slug', $job->slug ?? '') }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả công việc <span class="text-danger">*</span></label>
                        <textarea name="job_description" class="form-control ckeditor" required>
                            {{ old('job_description', $job->job_description ?? '') }}
                        </textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Yêu cầu công việc <span class="text-danger">*</span></label>
                        <textarea name="requirements" class="form-control ckeditor" required>
                            {{ old('requirements', $job->requirements ?? '') }}
                        </textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phúc lợi của công việc <span class="text-danger">*</span></label>
                        <textarea name="job_benefit" class="form-control ckeditor" required>
                            {{ old('job_benefit', $job->job_benefit ?? '') }}
                        </textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Địa chỉ cụ thể (số nhà, ngõ...) <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control"
                                value="{{ old('location', $job->location ?? '') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <select name="province_id" class="form-select select2" required>
                                <option value="">-- Chọn tỉnh/thành phố --</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}"
                                        {{ old('province_id', $job->province_id ?? '') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lương</label>
                            <input type="text" name="salary" class="form-control"
                                value="{{ old('salary', $job->salary ?? '') }}">
                            <small class="text-muted">
                                Vui lòng nhập mức lương (ví dụ: 10.000.000 VNĐ) hoặc ghi 'Thỏa thuận' nếu không cố định.
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Học vấn yêu cầu</label>
                            <input type="text" name="required_education" class="form-control"
                                value="{{ old('required_education', $job->required_education ?? '') }}">
                            <small class="text-muted">
                                VD: Cử nhân, Đại học, Thạc sĩ,...
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Kinh nghiệm yêu cầu</label>
                            <input type="text" name="required_exp" class="form-control"
                                value="{{ old('required_exp', $job->required_exp ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Loại công việc <span class="text-danger">*</span></label>
                            <input type="text" name="job_type" class="form-control"
                                value="{{ old('job_type', $job->job_type ?? '') }}" required>
                            <small class="text-muted">
                                Full-time, Part-time, Remote, ...
                            </small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Công việc nổi bật</label>
                        <div style="display:flex;align-items: center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_hot" id="is_hot_yes" value="yes"
                                    {{ old('is_hot', $job->is_hot ?? '') === 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_hot_yes">
                                    Có nổi bật
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="radio" name="is_hot" id="is_hot_no" value="no"
                                    {{ old('is_hot', $job->is_hot ?? '') === 'no' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_hot_no">
                                    Không nổi bật
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">Chọn "Có" nếu bạn muốn làm nổi bật tin tuyển dụng này.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày đăng <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-calendar"></i>
                                </span>
                                <input type="text" name="posted_date" class="form-control datepicker-autohide"
                                    value="{{ old('posted_date', $job->posted_date ?? '') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày hết hạn <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-calendar"></i>
                                </span>
                                <input type="text" name="expiry_date" class="form-control datepicker-autohide"
                                    value="{{ old('expiry_date', $job->expiry_date ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kỹ năng</label>
                        <select name="skills[]" class="form-control select2" multiple="multiple">
                            @foreach ($allSkills as $skill)
                                <option value="{{ $skill->skill_id }}"
                                    {{ isset($job) && in_array($skill->skill_id, $job->skills->pluck('skill_id')->toArray() ?? []) ? 'selected' : '' }}>
                                    {{ $skill->skill_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control select2" required>
                            <option value="">Chọn danh mục</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}"
                                    {{ isset($job) && $job->category_id == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nhà tuyển dụng <span class="text-danger">*</span></label>
                        <select name="employer_id" class="form-control select2" required>
                            <option value="">Chọn nhà tuyển dụng</option>
                            @foreach ($employers as $employer)
                                <option value="{{ $employer->employer_id }}"
                                    {{ isset($job) && $job->employer_id == $employer->employer_id ? 'selected' : '' }}>
                                    {{ $employer->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vị trí chức vụ <span class="text-danger">*</span></label>
                        <select name="position_id" class="form-control select2" required>
                            <option value="">Chọn vị trí</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ isset($job) && $job->position_id == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái phê duyệt <span class="text-danger">*</span></label>
                        <select name="approval_status" class="form-control" required>
                            <option value="pending"
                                {{ old('approval_status', $job->approval_status ?? 'pending') == 'pending' ? 'selected' : '' }}>
                                Chờ duyệt</option>
                            <option value="approved"
                                {{ old('approval_status', $job->approval_status ?? '') == 'approved' ? 'selected' : '' }}>
                                Đã duyệt</option>
                            <option value="rejected"
                                {{ old('approval_status', $job->approval_status ?? '') == 'rejected' ? 'selected' : '' }}>
                                Bị từ chối</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="btn btn-success">{{ isset($job) ? 'Cập nhật công việc' : 'Thêm công việc' }}
                    </button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('Admin.snippets.ckeditor_file_management')

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

            $('.text-to-slug[name="job_title"]').on('input', function() {
                var name = $(this).val();
                var slug = slugify(name);
                $('.text-to-slug[name="slug"]').val(slug);
            });

            @if (isset($job))
                var initialName = $('.text-to-slug[name="job_title"]').val();
                if (initialName) {
                    var initialSlug = slugify(initialName);
                    $('.text-to-slug[name="slug"]').val(initialSlug);
                }
            @endif
        });
    </script>
@endpush
