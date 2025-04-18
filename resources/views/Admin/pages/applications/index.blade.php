@extends('Admin.layouts.master')

@section('pageTitle', 'Đơn ứng tuyển')

@section('content')
    @include('Admin.snippets.page_header')

    <div class="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách đơn ứng tuyển</h5>
                {{-- <a href="{{ route('admin.applications.create') }}" class="btn btn-primary">Thêm mới</a> --}}
            </div>

            <div class="card-body">
                <form action="{{ route('admin.applications.index') }}" method="GET" class="mb-3">
                    <div class="row g-2">
                        {{-- <div class="col-12 col-md-4">
                            <x-clearable-input name="search" placeholder="Tìm kiếm theo ứng viên" :value="request('search')" />
                        </div> --}}
                        <div class="col-12 col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    Chờ duyệt
                                </option>
                                <option value="interviewed" {{ request('status') == 'interviewed' ? 'selected' : '' }}>
                                    Đã phỏng vấn
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    Từ chối
                                </option>
                                <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>
                                    Đã tuyển
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                        </div>
                    </div>
                </form>

                <x-table-wrapper-cms :headers="['Ứng viên', 'Công việc', 'Ngày ứng tuyển', 'Trạng thái', 'Hành động']">
                    @foreach ($applications as $application)
                        <tr>
                            <td>
                                @if ($cv = optional($application->candidate)->resume)
                                    <a href="{{ asset('storage/' . $cv) }}" target="_blank">Xem CV</a>
                                @else
                                    <span>Không có CV</span>
                                @endif
                            </td>
                            <td>{{ $application->job->job_title ?? 'Không có' }}</td>
                            <td>{{ $application->application_date }}</td>
                            <td>
                                @php
                                    $statusLabels = [
                                        'hired' => 'Đã tuyển',
                                        'interviewed' => 'Đã phỏng vấn',
                                        'pending' => 'Chờ duyệt',
                                        'rejected' => 'Bị từ chối',
                                    ];

                                    $statusClasses = [
                                        'hired' => 'btn-success',
                                        'interviewed' => 'btn-primary',
                                        'pending' => 'btn-warning',
                                        'rejected' => 'btn-danger',
                                    ];

                                    $currentStatus = $application->status;
                                @endphp

                                <div class="dropdown">
                                    <button
                                        class="btn btn-sm dropdown-toggle {{ $statusClasses[$currentStatus] ?? 'btn-secondary' }}"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $statusLabels[$currentStatus] ?? 'Không xác định' }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach ($statusLabels as $key => $label)
                                            <li>
                                                <a class="dropdown-item change-status"
                                                    data-url="{{ route('admin.applications.update-status', $application->application_id) }}"
                                                    data-status="{{ $key }}">{{ $label }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center">
                                <x-action-dropdown editRoute="admin.applications.edit"
                                    deleteRoute="admin.applications.destroy" :id="$application->application_id" />
                            </td>
                        </tr>
                    @endforeach
                </x-table-wrapper-cms>

                <x-pagination-links-cms :paginator="$applications" />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            const statusLabels = {
                                pending: "Chờ duyệt",
                                rejected: "Bị từ chối",
                                interviewed: "Đã phỏng vấn",
                                hired: "Đã tuyển"
                            };

                            const statusClasses = {
                                pending: "btn-warning",
                                rejected: "btn-danger",
                                interviewed: "btn-primary",
                                hired: "btn-success"
                            };

                            button
                                .text(statusLabels[newStatus])
                                .removeClass(
                                    "btn-warning btn-success btn-danger btn-primary btn-success"
                                )
                                .addClass(statusClasses[newStatus]);
                        } else {
                            alert("Có lỗi xảy ra!");
                        }
                    },
                    error: function() {
                        alert("Có lỗi xảy ra khi cập nhật trạng thái.");
                    }
                });
            });
        });
    </script>
@endpush
