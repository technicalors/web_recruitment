@extends('Admin.layouts.master')

@section('pageTitle', 'Công việc')

@section('content')
    @include('Admin.snippets.page_header')

    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách công việc</h5>
                <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">Thêm mới</a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.jobs.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <x-clearable-input name="search" placeholder="Tìm kiếm theo tiêu đề công việc"
                                :value="request('search')" />
                        </div>
                        <div class="col-md-3">
                            <select name="category_id" class="form-control select2">
                                <option value="">Tất cả danh mục</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }} "
                                        {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="approval_status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>
                                    Chờ
                                    duyệt</option>
                                <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>
                                    Đã
                                    duyệt</option>
                                <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>
                                    Bị
                                    từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                        </div>
                    </div>
                </form>

                <!-- Alert Container -->
                <div id="alert-container" class="mb-3"></div>

                <div id="bulk-actions" class="mb-3 d-flex align-items-center gap-2">
                    <select id="bulk-status" class="form-select" style="width: 200px;">
                        <option value="">-- Cập nhật trạng thái --</option>
                        <option value="pending">Chờ duyệt</option>
                        <option value="approved">Đã duyệt</option>
                        <option value="rejected">Bị từ chối</option>
                    </select>

                    <button class="btn btn-success" id="btn-bulk-update">Cập nhật</button>
                    <button class="btn btn-danger" id="btn-bulk-delete">Xóa</button>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="check-all" /></th>
                            <th>Tiêu đề</th>
                            <th>Danh mục</th>
                            <th>Loại công việc</th>
                            <th>Mức lương</th>
                            <th class="text-center">Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <td><input type="checkbox" class="job-checkbox" value="{{ $job->job_id }}"></td>
                                <td>{{ $job->job_title }}</td>
                                <td>{{ $job->category->category_name ?? 'Không có' }}</td>
                                <td>{{ $job->job_type }}</td>
                                <td>{{ \App\Helpers\NumberHelper::formatSalary($job->salary) }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-sm dropdown-toggle 
                                            {{ $job->approval_status == 'pending' ? 'btn-warning' : ($job->approval_status == 'approved' ? 'btn-success' : 'btn-danger') }}"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if ($job->approval_status == 'pending')
                                                Chờ duyệt
                                            @elseif ($job->approval_status == 'approved')
                                                Đã duyệt
                                            @else
                                                Bị từ chối
                                            @endif
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item change-status"
                                                    data-url="{{ route('admin.jobs.update-status', $job->job_id) }}"
                                                    data-status="pending">Chờ duyệt</a></li>
                                            <li><a class="dropdown-item change-status"
                                                    data-url="{{ route('admin.jobs.update-status', $job->job_id) }}"
                                                    data-status="approved">Đã duyệt</a></li>
                                            <li><a class="dropdown-item change-status"
                                                    data-url="{{ route('admin.jobs.update-status', $job->job_id) }}"
                                                    data-status="rejected">Bị từ chối</a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <x-action-dropdown editRoute="admin.jobs.edit" deleteRoute="admin.jobs.destroy"
                                        :id="$job->job_id" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $jobs->appends(request()->query())->links('Admin.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // tạo Select2
            $('.select2').select2({
                placeholder: "Chọn một mục",
                allowClear: true
            });

            // Function to show alerts
            function showAlert(type, message) {
                $('#alert-container').html(`
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                `);
            }

            $(".change-status").click(function(e) {
                e.preventDefault();
                let newStatus = $(this).data("status");
                let updateUrl = $(this).data("url");
                let button = $(this).closest(".dropdown").find("button");

                $.ajax({
                    url: updateUrl,
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        approval_status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            let statusText = {
                                pending: "Chờ duyệt",
                                approved: "Đã duyệt",
                                rejected: "Bị từ chối"
                            };
                            let statusClass = {
                                pending: "btn-warning",
                                approved: "btn-success",
                                rejected: "btn-danger"
                            };

                            button.text(statusText[newStatus]);
                            button.attr("class", "btn btn-sm dropdown-toggle " + statusClass[
                                newStatus]);
                            showAlert('success', 'Cập nhật trạng thái thành công!');
                        } else {
                            showAlert('danger', 'Có lỗi xảy ra!');
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Có lỗi xảy ra khi cập nhật trạng thái.');
                    }
                });
            });

            // Check all
            $('#check-all').on('change', function() {
                $('.job-checkbox').prop('checked', this.checked);
            });

            // Bulk update
            $('#btn-bulk-update').click(function() {
                let selected = $('.job-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                let status = $('#bulk-status').val();
                if (!selected.length || !status) return alert('Vui lòng chọn công việc và trạng thái.');

                $.ajax({
                    url: '{{ route('admin.jobs.bulkUpdate') }}',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selected,
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            location.reload();
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Có lỗi xảy ra khi cập nhật trạng thái.');
                    }
                });
            });


            // Bulk delete
            $('#btn-bulk-delete').click(function() {
                let selected = $('.job-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (!selected.length || !confirm('Bạn có chắc muốn xóa?')) return;

                $.ajax({
                    url: '{{ route('admin.jobs.bulkDelete') }}',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selected
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            location.reload();
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Có lỗi xảy ra khi xóa công việc.');
                    }
                });
            });
        });
    </script>
@endpush
