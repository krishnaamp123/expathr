@extends('admin.layout.app')
@section('title', 'Pipeline')
@section('content')

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pipeline</h1>

    <!-- Toast Container -->
    <div aria-live="polite" aria-atomic="true" class="position-fixed" style="top: 4.5rem; right: 20rem; z-index: 1050;">
        <div id="successToast" class="toast text-white" style="background-color: #72A28A; min-width: 300px;" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true">
            <div class="toast-header text-white" style="background-color: #72A28A;">
                <strong class="mr-auto">Success</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                <!-- Pesan sukses -->
            </div>
        </div>

        <div id="failedToast" class="toast text-white" style="background-color: #c03535; min-width: 300px;" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true">
            <div class="toast-header text-white" style="background-color: #c03535;">
                <strong class="mr-auto">Error</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                <!-- Pesan gagal -->
            </div>
        </div>
    </div>

    <p class="mb-3">Applicant's main data to the job</p>

    <form action="{{ route('getUserHrjob') }}" method="GET" class="d-flex align-items-end">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
        <input type="hidden" name="end_date" value="{{ request('end_date') }}">

        <div class="me-3">
            <label for="id_job">Filter by Job</label>
            <select name="id_job" id="id_job" class="form-control select2">
                <option value="">All Jobs</option>
                @foreach($hrjobss as $hrjob)
                    <option value="{{ $hrjob->id }}" {{ request('id_job') == $hrjob->id ? 'selected' : '' }}>
                        {{ $hrjob->job_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        </div>
    </form>

    <!-- Topbar for Filtering by Status -->
    <div class="mb-3">
        <ul class="nav nav-pills nav-justified custom-nav">
            @foreach ($statuses as $filterStatus)
                <li class="nav-item">
                    <a href="{{ route('getUserHrjob', [
                        'status' => $filterStatus,
                        'id_job' => request('id_job'),
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date')
                    ]) }}"
                       class="nav-link {{ $status === $filterStatus ? 'active' : '' }}">
                        {{ ucwords(str_replace('_', ' ', $filterStatus)) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    @if (request('status') === 'hr_interview')
                    <button
                        type="button"
                        class="btn btn-sm mr-2"
                        style="background-color: #72A28A; color: white;"
                        data-bs-toggle="modal"
                        data-bs-target="#addInterviewModal">
                        <i class="fas fa-plus"></i> Add
                    </button>

                    @include('admin.interview.storemodal', [
                        'userhrjobs' => $userhrjobss,
                        'users' => $users,
                    ])
                    <a href="{{ route('exportInterview') }}" class="btn btn-sm mr-2" style="background-color: #000; color: white;">
                        <i class="fas fa-file-excel"></i> Export All
                    </a>
                    <!-- Tombol Export -->
                    <button class="btn btn-sm mr-2" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
                        <i class="fas fa-calendar-check"></i> Export Date
                    </button>

                    <button id="reject-selected" class="btn btn-sm"  style="background-color: #c03535; color: white;"><i class="fas fa-times"></i> Reject Selected</button>

                    <!-- Modal Popup -->
                    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalLabel">Export Date Range</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('exportdateInterview') }}" method="GET">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_date" class="mb-2"><strong>Start Date:</strong></label>
                                            <input id="start_date" name="start_date" class="form-control datepicker datepicker-input" placeholder="Start Date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-2"><strong>End Date:</strong></label>
                                            <input id="end_date" name="end_date" class="form-control datepicker datepicker-input" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @elseif (request('status') === 'user_interview')
                    <div class="d-flex align-items-center">
                        <button
                            type="button"
                            class="btn btn-sm mr-2"
                            style="background-color: #72A28A; color: white;"
                            data-bs-toggle="modal"
                            data-bs-target="#addUserInterviewModal">
                            <i class="fas fa-plus"></i> Add
                        </button>

                        @include('admin.userinterview.storemodal', [
                            'userhrjobs' => $userhrjobss,
                            'users' => $users,
                        ])
                    <a href="{{ route('exportUserInterview') }}" class="btn btn-sm mr-2" style="background-color: #000; color: white;">
                        <i class="fas fa-file-excel"></i> Export All
                    </a>
                    <!-- Tombol Export -->
                    <button class="btn btn-sm mr-2" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
                        <i class="fas fa-calendar-check"></i> Export Date
                    </button>

                    <button id="reject-selected" class="btn btn-sm"  style="background-color: #c03535; color: white;"><i class="fas fa-times"></i> Reject Selected</button>

                    <!-- Modal Popup -->
                    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalLabel">Export Date Range</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('exportdateUserInterview') }}" method="GET">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_date" class="mb-2"><strong>Start Date:</strong></label>
                                            <input id="start_date" name="start_date" class="form-control datepicker datepicker-input" placeholder="Start Date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-2"><strong>End Date:</strong></label>
                                            <input id="end_date" name="end_date" class="form-control datepicker datepicker-input" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    @elseif (request('status') === 'skill_test')
                    <div class="d-flex align-items-center">
                        <button
                            type="button"
                            class="btn btn-sm mr-2"
                            style="background-color: #72A28A; color: white;"
                            data-bs-toggle="modal"
                            data-bs-target="#addSkillTestModal">
                            <i class="fas fa-plus"></i> Add
                        </button>

                        @include('admin.skilltest.storemodal', [
                            'userhrjobs' => $userhrjobss,
                            'users' => $users,
                        ])
                    <a href="{{ route('exportSkillTest') }}" class="btn btn-sm mr-2" style="background-color: #000; color: white;">
                        <i class="fas fa-file-excel"></i> Export All
                    </a>
                    <!-- Tombol Export -->
                    <button class="btn btn-sm mr-2" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
                        <i class="fas fa-calendar-check"></i> Export Date
                    </button>

                    <button id="reject-selected" class="btn btn-sm"  style="background-color: #c03535; color: white;"><i class="fas fa-times"></i> Reject Selected</button>

                    <!-- Modal Popup -->
                    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalLabel">Export Date Range</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('exportdateSkillTest') }}" method="GET">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_date" class="mb-2"><strong>Start Date:</strong></label>
                                            <input id="start_date" name="start_date" class="form-control datepicker datepicker-input" placeholder="Start Date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-2"><strong>End Date:</strong></label>
                                            <input id="end_date" name="end_date" class="form-control datepicker datepicker-input" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    @elseif (request('status') === 'phone_screen')
                    <div class="d-flex align-items-center">
                        <button
                            type="button"
                            class="btn btn-sm mr-2"
                            style="background-color: #72A28A; color: white;"
                            data-bs-toggle="modal"
                            data-bs-target="#addPhoneScreenModal">
                            <i class="fas fa-plus"></i> Add
                        </button>

                        @include('admin.phonescreen.storemodal', [
                            'userhrjobs' => $userhrjobss,
                            'users' => $users,
                        ])
                    <a href="{{ route('exportPhoneScreen') }}" class="btn btn-sm mr-2" style="background-color: #000; color: white;">
                        <i class="fas fa-file-excel"></i> Export All
                    </a>
                    <!-- Tombol Export -->
                    <button class="btn btn-sm mr-2" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
                        <i class="fas fa-calendar-check"></i> Export Date
                    </button>

                    <button id="reject-selected" class="btn btn-sm"  style="background-color: #c03535; color: white;"><i class="fas fa-times"></i> Reject Selected</button>

                    <!-- Modal Popup -->
                    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalLabel">Export Date Range</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('exportdatePhoneScreen') }}" method="GET">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_date" class="mb-2"><strong>Start Date:</strong></label>
                                            <input id="start_date" name="start_date" class="form-control datepicker datepicker-input" placeholder="Start Date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-2"><strong>End Date:</strong></label>
                                            <input id="end_date" name="end_date" class="form-control datepicker datepicker-input" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    @elseif (request('status') === 'reference_check')
                    <div class="d-flex align-items-center">
                        <button
                            type="button"
                            class="btn btn-sm mr-2"
                            style="background-color: #72A28A; color: white;"
                            data-bs-toggle="modal"
                            data-bs-target="#addReferenceCheckModal">
                            <i class="fas fa-plus"></i> Add
                        </button>

                        @include('admin.referencecheck.storemodal', [
                            'userhrjobs' => $userhrjobss,
                            'references' => $references,
                        ])
                    <a href="{{ route('exportReferenceCheck') }}" class="btn btn-sm mr-2" style="background-color: #000; color: white;">
                        <i class="fas fa-file-excel"></i> Export All
                    </a>
                    <!-- Tombol Export -->
                    <button class="btn btn-sm mr-2" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
                        <i class="fas fa-calendar-check"></i> Export Date
                    </button>

                    <button id="reject-selected" class="btn btn-sm"  style="background-color: #c03535; color: white;"><i class="fas fa-times"></i> Reject Selected</button>

                    <!-- Modal Popup -->
                    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalLabel">Export Date Range</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('exportdateReferenceCheck') }}" method="GET">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_date" class="mb-2"><strong>Start Date:</strong></label>
                                            <input id="start_date" name="start_date" class="form-control datepicker datepicker-input" placeholder="Start Date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-2"><strong>End Date:</strong></label>
                                            <input id="end_date" name="end_date" class="form-control datepicker datepicker-input" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>

                    @elseif (request('status') === 'offering')
                    <div class="d-flex align-items-center">
                        <button
                            type="button"
                            class="btn btn-sm mr-2"
                            style="background-color: #72A28A; color: white;"
                            data-bs-toggle="modal"
                            data-bs-target="#addOfferingModal">
                            <i class="fas fa-plus"></i> Add
                        </button>

                        @include('admin.offering.storemodal', [
                            'userhrjobs' => $userhrjobss,
                        ])
                    <a href="{{ route('exportOffering') }}" class="btn btn-sm mr-2" style="background-color: #000; color: white;">
                        <i class="fas fa-file-excel"></i> Export All
                    </a>
                    <!-- Tombol Export -->
                    <button class="btn btn-sm mr-2" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
                        <i class="fas fa-calendar-check"></i> Export Date
                    </button>

                    <button id="reject-selected" class="btn btn-sm"  style="background-color: #c03535; color: white;"><i class="fas fa-times"></i> Reject Selected</button>

                    <!-- Modal Popup -->
                    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalLabel">Export Date Range</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('exportdateOffering') }}" method="GET">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_date" class="mb-2"><strong>Start Date:</strong></label>
                                            <input id="start_date" name="start_date" class="form-control datepicker datepicker-input" placeholder="Start Date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-2"><strong>End Date:</strong></label>
                                            <input id="end_date" name="end_date" class="form-control datepicker datepicker-input" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>

                    @else
                    <button
                        type="button"
                        class="btn btn-sm mr-2"
                        style="background-color: #72A28A; color: white;"
                        data-bs-toggle="modal"
                        data-bs-target="#addUserHrjobModal">
                        <i class="fas fa-plus"></i> Add
                    </button>

                    @include('admin.userhrjob.storemodal', [
                        'hrjob' => $hrjobss,
                        'user' => $userss,
                    ])

                        @if(request('status') === 'rejected')
                            <a href="{{ route('exportRejected', ['status' => request('status')]) }}" class="btn btn-sm mr-2" style="background-color: #000; color: white;">
                                <i class="fas fa-file-excel"></i> Export All
                            </a>
                            <!-- Tombol Export -->
                            <button class="btn btn-sm mr-2" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModalRejected">
                                <i class="fas fa-calendar-check"></i> Export Date
                            </button>
                        @else
                            <a href="{{ route('exportUserHrjob', ['status' => request('status')]) }}" class="btn btn-sm mr-2" style="background-color: #000; color: white;">
                                <i class="fas fa-file-excel"></i> Export All
                            </a>
                            <!-- Tombol Export -->
                            <button class="btn btn-sm mr-2" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
                                <i class="fas fa-calendar-check"></i> Export Date
                            </button>
                        @endif

                    <button id="reject-selected" class="btn btn-sm"  style="background-color: #c03535; color: white;"><i class="fas fa-times"></i> Reject Selected</button>

                    <!-- Modal Popup -->
                    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalLabel">Export Date Range</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('exportdateUserHrjob') }}" method="GET">
                                    <input type="hidden" name="status" value="{{ $status ?? 'all' }}">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_date" class="mb-2"><strong>Start Date:</strong></label>
                                            <input id="start_date" name="start_date" class="form-control datepicker datepicker-input" placeholder="Start Date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-2"><strong>End Date:</strong></label>
                                            <input id="end_date" name="end_date" class="form-control datepicker datepicker-input" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="exportModalRejected" tabindex="-1" aria-labelledby="exportModalRejectedLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalRejectedLabel">Export Date Range</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('exportdateRejected') }}" method="GET">
                                    <input type="hidden" name="status" value="{{ $status ?? 'all' }}">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="start_date" class="mb-2"><strong>Start Date:</strong></label>
                                            <input id="start_date" name="start_date" class="form-control datepicker datepicker-input" placeholder="Start Date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-2"><strong>End Date:</strong></label>
                                            <input id="end_date" name="end_date" class="form-control datepicker datepicker-input" placeholder="End Date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endif
                </div>
            <!-- Form Filter Tanggal -->
            <div class="d-flex justify-content-end">
                <form action="{{ route('getUserHrjob') }}" method="GET" class="form-inline">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="id_job" value="{{ request('id_job') }}">

                    <div class="form-group mx-sm-2 mb-2">
                        <label for="start_date" class="sr-only">Start Date</label>
                        <input class="form-control datepicker datepicker-input" style="width: 100px;" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date">
                    </div>
                    <span class="mx-2"><i class="fas fa-arrow-right"></i></span>
                    <div class="form-group mx-sm-2 mb-2">
                        <label for="end_date" class="sr-only">End Date</label>
                        <input class="form-control datepicker datepicker-input" style="width: 100px;" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="End Date">
                    </div>

                    <a href="{{ route('getUserHrjob', [
                        'status' => request('status'),
                        'id_job' => request('id_job')
                    ]) }}" class="btn btn-secondary btn-sm mb-2 mr-2"><i class="fas fa-times"></i> Clear Date</a>
                    <button type="submit" class="btn btn-primary btn-sm mb-2"><i class="fas fa-filter"></i> Filter</button>
                </form>
            </div>
        </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="small text-center small">
                            <th>
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>ID</th>
                            <th>Job</th>
                            <th>Applicant</th>
                            @if (request('status') === 'hr_interview')
                                <th>Interviewer</th>
                                <th>Interview Date</th>
                                <th>Interview Time</th>
                                <th>Location</th>
                                <th>Link</th>
                                <th>Confirmed</th>
                                <th>Interview Process</th>
                                <th>Updated At</th>
                                <th>Rating</th>
                                <th>Comment</th>
                            @elseif (request('status') === 'user_interview')
                                <th>Interviewer</th>
                                <th>Interview Date</th>
                                <th>Interview Time</th>
                                <th>Location</th>
                                <th>Link</th>
                                <th>Confirmed</th>
                                <th>Interview Process</th>
                                <th>Updated At</th>
                                <th>Rating</th>
                                <th>Comment</th>
                            @elseif (request('status') === 'skill_test')
                                <th>Score</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            @elseif (request('status') === 'phone_screen')
                                <th>Phone Screen Date</th>
                                <th>Phone Screen Time</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            @elseif (request('status') === 'reference_check')
                                <th>Reference Check</th>
                                <th>Updated At</th>
                            @elseif (request('status') === 'offering')
                                <th>Offering File</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            @else
                                <th>Salary Expectation</th>
                                <th>Availability</th>
                                <th>Applied At</th>
                                @if (request('status') === 'applicant')
                                    <th>Average Score</th>
                                @else
                                    <th>Updated At</th>
                                @endif
                            @endif
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userhrjobs as $row)
                            @if (request('status') === null || request('status') === $row->status)
                                <tr class="small" data-id="{{ $row->id }}">
                                    <td>
                                        <input type="checkbox" name="selected_jobs[]" value="{{ $row->id }}" class="select-job">
                                    </td>
                                    <td>{{ $row->id }}</td>
                                    <td data-field="job_name">{{ $row->hrjob->job_name ?? 'No Job' }}</td>
                                    <td data-field="fullname">
                                        <a
                                            href="#"
                                            class="btn p-0"
                                            style="font-size: 0.8rem;"
                                            data-toggle="modal"
                                            data-target="#userDetailsModal-{{ $row->id }}">
                                            {{ \Illuminate\Support\Str::limit($row->user->fullname ?? 'No Applicant', 20, '...') }}
                                        </a>
                                    </td>
                                    @if (request('status') === 'hr_interview')
                                        <td data-field="interviewers">
                                            @if ($row->interviews->isNotEmpty())
                                                {{ $row->interviews->flatMap(fn($interview) => $interview->interviewers->pluck('fullname'))->implode(', ') }}
                                            @else
                                                <span>No Interviewers</span>
                                            @endif
                                        </td>
                                        <td data-field="interview_date">
                                            {{ $row->interviews->first()->interview_date ?? 'Not Scheduled' }}
                                        </td>
                                        <td data-field="time">
                                            {{ $row->interviews->first()->time ?? 'Not Timed' }}
                                        </td>
                                        <td data-field="location">
                                            {{ $row->interviews->first()->location ?? 'Not Located' }}
                                        </td>
                                        <td data-field="link">
                                            @if ($row->interviews->first()?->link)
                                                <a href="{{ $row->interviews->first()->link }}" target="_blank" title="{{ $row->interviews->first()->link }}">
                                                    {{ \Illuminate\Support\Str::limit($row->interviews->first()->link, 20, '...') }}
                                                </a>
                                            @else
                                                No Link
                                            @endif
                                        </td>
                                        <td data-field="arrival">
                                            @if ($row->interviews->isNotEmpty() && $row->interviews->first()->arrival === 'yes')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @elseif ($row->interviews->isNotEmpty() && ($row->interviews->first()->arrival === 'no' || is_null($row->interviews->first()->arrival)))
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td data-field="interviewed">
                                            @if ($row->interviews->isNotEmpty() && $row->interviews->first()->interviewed === 'yes')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @elseif ($row->interviews->isNotEmpty() && ($row->interviews->first()->interviewed === 'no' || is_null($row->interviews->first()->interviewed)))
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td data-field="updated_at">
                                            {{ $row->interviews && $row->interviews->first() && $row->interviews->first()->updated_at
                                                ? $row->interviews->first()->updated_at->format('d-m-Y H:i:s')
                                                : 'Not Updated' }}
                                        </td>
                                        <td data-field="rating">
                                            {{ $row->interviews->first()->rating ?? 'Not Rated' }}
                                        </td>
                                        <td data-field="comment">
                                            @if ($row->interviews->first()?->comment)
                                                <span
                                                    title="{{ $row->interviews->first()->comment }}">
                                                    {{ \Illuminate\Support\Str::limit($row->interviews->first()->comment, 20, '...') }}
                                                </span>
                                            @else
                                                Not Commented
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST" class="update-status-form">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}">
                                                    @foreach ($statuses as $availableStatus)
                                                        <option value="{{ $availableStatus }}" {{ $row->status === $availableStatus ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $availableStatus)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            @php
                                                $interview = $row->interviews->first();
                                            @endphp

                                            @if ($interview && $interview->id)
                                                @if(Auth::user()->role !== 'interviewer')
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm my-1"
                                                        style="background-color: #969696; color: white;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editInterviewModal{{ $interview->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    @include('admin.interview.updatemodal', [
                                                        'id' => $interview->id,
                                                        'interview' => $interview,
                                                        'userhrjobs' => $userhrjobss,
                                                        'users' => $users,
                                                    ])
                                                @endif
                                                <button
                                                    type="button"
                                                    class="btn btn-sm my-1"
                                                    style="background-color: #FFA500; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRatingModal{{ $interview->id }}">
                                                    <i class="fas fa-star"></i>
                                                </button>

                                                <!-- Include Modal -->
                                                @include('admin.interview.ratingmodal', [
                                                    'id' => $interview->id,
                                                    'rating' => $interview->rating,
                                                    'comment' => $interview->comment,
                                                    'interviewed' => $interview->interviewed
                                                ])
                                                <form method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm delete-btn"
                                                            style="background-color: #c03535; color: white;"
                                                            data-url="{{ route('destroyInterview', $interview->id) }}"
                                                            data-confirm-message="Are you sure you want to delete this interview?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-danger">Create a valid interview first !</span>
                                            @endif
                                        </td>
                                    @elseif (request('status') === 'user_interview')
                                        {{-- <td data-field="user_interviewers">
                                            @if ($row->userinterviews->isNotEmpty())
                                                <ul>
                                                    @foreach ($row->userinterviews as $userinterview)
                                                        @foreach ($userinterview->user_interviewers as $user_interviewer)
                                                            <li>{{ $user_interviewer->fullname }} </li>
                                                        @endforeach
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span>No Interviewers</span>
                                            @endif
                                        </td> --}}
                                        <td data-field="user_interviewers">
                                            @if ($row->userinterviews->isNotEmpty())
                                                {{ $row->userinterviews->flatMap(fn($userinterview) => $userinterview->user_interviewers->pluck('fullname'))->implode(', ') }}
                                            @else
                                                <span>No Interviewers</span>
                                            @endif
                                        </td>
                                        <td data-field="interview_date">
                                            {{ $row->userinterviews->first()->interview_date ?? 'Not Scheduled' }}
                                        </td>
                                        <td data-field="time">
                                            {{ $row->userinterviews->first()->time ?? 'Not Timed' }}
                                        </td>
                                        <td data-field="location">
                                            {{ $row->userinterviews->first()->location ?? 'Not Located' }}
                                        </td>
                                        <td data-field="link">
                                            @if ($row->userinterviews->first()?->link)
                                                <a href="{{ $row->userinterviews->first()->link }}" target="_blank" title="{{ $row->userinterviews->first()->link }}">
                                                    {{ \Illuminate\Support\Str::limit($row->userinterviews->first()->link, 20, '...') }}
                                                </a>
                                            @else
                                                No Link
                                            @endif
                                        </td>
                                        <td data-field="arrival">
                                            @if ($row->userinterviews->isNotEmpty() && $row->userinterviews->first()->arrival === 'yes')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @elseif ($row->userinterviews->isNotEmpty() && ($row->userinterviews->first()->arrival === 'no' || is_null($row->userinterviews->first()->arrival)))
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td data-field="interviewed">
                                            @if ($row->userinterviews->isNotEmpty() && $row->userinterviews->first()->interviewed === 'yes')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @elseif ($row->userinterviews->isNotEmpty() && ($row->userinterviews->first()->interviewed === 'no' || is_null($row->userinterviews->first()->interviewed)))
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td data-field="updated_at">
                                            {{ $row->userinterviews && $row->userinterviews->first() && $row->userinterviews->first()->updated_at
                                                ? $row->userinterviews->first()->updated_at->format('d-m-Y H:i:s')
                                                : 'Not Updated' }}
                                        </td>
                                        <td data-field="rating">
                                            {{ $row->userinterviews->first()->rating ?? 'Not Rated' }}
                                        </td>
                                        <td data-field="comment">
                                            @if ($row->userinterviews->first()?->comment)
                                                <span
                                                    title="{{ $row->userinterviews->first()->comment }}">
                                                    {{ \Illuminate\Support\Str::limit($row->userinterviews->first()->comment, 20, '...') }}
                                                </span>
                                            @else
                                                Not Commented
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST" class="update-status-form">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}">
                                                    @foreach ($statuses as $availableStatus)
                                                        <option value="{{ $availableStatus }}" {{ $row->status === $availableStatus ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $availableStatus)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            @php
                                                $userinterview = $row->userinterviews->first();
                                            @endphp

                                            @if ($userinterview && $userinterview->id)
                                                @if(Auth::user()->role !== 'interviewer')
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm my-1"
                                                        style="background-color: #969696; color: white;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editUserInterviewModal{{ $userinterview->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    @include('admin.userinterview.updatemodal', [
                                                        'id' => $userinterview->id,
                                                        'userinterview' => $userinterview,
                                                        'userhrjobs' => $userhrjobss,
                                                        'users' => $users,
                                                    ])
                                                @endif

                                                <button
                                                    type="button"
                                                    class="btn btn-sm my-1"
                                                    style="background-color: #FFA500; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUserRatingModal{{ $userinterview->id }}">
                                                    <i class="fas fa-star"></i>
                                                </button>

                                                <!-- Include Modal -->
                                                @include('admin.userinterview.ratingmodal', [
                                                    'id' => $userinterview->id,
                                                    'rating' => $userinterview->rating,
                                                    'comment' => $userinterview->comment,
                                                    'interviewed' => $userinterview->interviewed
                                                ])
                                                <form method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm delete-btn"
                                                            style="background-color: #c03535; color: white;"
                                                            data-url="{{ route('destroyUserInterview', $userinterview->id) }}"
                                                            data-confirm-message="Are you sure you want to delete this user interview?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-danger">Create a valid interview first !</span>
                                            @endif
                                        </td>
                                    @elseif (request('status') === 'skill_test')
                                        <td data-field="score">
                                            {{ $row->skilltests->first()->score ?? 'Not Scored' }}
                                        </td>
                                        <td data-field="rating">
                                            {{ $row->skilltests->first()->rating ?? 'Not Rated' }}
                                        </td>
                                        <td data-field="comment">
                                            @if ($row->skilltests->first()?->comment)
                                            <span
                                                title="{{ $row->skilltests->first()->comment }}">
                                                {{ \Illuminate\Support\Str::limit($row->skilltests->first()->comment, 20, '...') }}
                                            </span>
                                            @else
                                                Not Commented
                                            @endif
                                        </td>
                                        <td data-field="created_at">
                                            {{ $row->skilltests && $row->skilltests->first() && $row->skilltests->first()->created_at
                                                ? $row->skilltests->first()->created_at->format('d-m-Y H:i:s')
                                                : 'Not Created' }}
                                        </td>
                                        <td data-field="updated_at">
                                            {{ $row->skilltests && $row->skilltests->first() && $row->skilltests->first()->updated_at
                                                ? $row->skilltests->first()->updated_at->format('d-m-Y H:i:s')
                                                : 'Not Updated' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST" class="update-status-form">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}">
                                                    @foreach ($statuses as $availableStatus)
                                                        <option value="{{ $availableStatus }}" {{ $row->status === $availableStatus ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $availableStatus)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            @php
                                                $skilltest = $row->skilltests->first();
                                            @endphp

                                            @if ($skilltest && $skilltest->id)
                                                <button
                                                    type="button"
                                                    class="btn btn-sm my-1"
                                                    style="background-color: #969696; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editSkillTestModal{{ $skilltest->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @include('admin.skilltest.updatemodal', [
                                                    'id' => $skilltest->id,
                                                    'skilltest' => $skilltest,
                                                    'userhrjobs' => $userhrjobss,
                                                ])

                                                <form method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm delete-btn"
                                                            style="background-color: #c03535; color: white;"
                                                            data-url="{{ route('destroySkillTest', $skilltest->id) }}"
                                                            data-confirm-message="Are you sure you want to delete this skill test?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-danger">Create a valid skill test first !</span>
                                            @endif
                                        </td>
                                    @elseif (request('status') === 'phone_screen')
                                        <td data-field="phonescreen_date">
                                            {{ $row->phonescreens->first()->phonescreen_date ?? 'Not Scheduled' }}
                                        </td>
                                        <td data-field="time">
                                            {{ $row->phonescreens->first()->time ?? 'Not Timed' }}
                                        </td>
                                        <td data-field="created_at">
                                            {{ $row->phonescreens && $row->phonescreens->first() && $row->phonescreens->first()->created_at
                                                ? $row->phonescreens->first()->created_at->format('d-m-Y H:i:s')
                                                : 'Not Created' }}
                                        </td>
                                        <td data-field="updated_at">
                                            {{ $row->phonescreens && $row->phonescreens->first() && $row->phonescreens->first()->updated_at
                                                ? $row->phonescreens->first()->updated_at->format('d-m-Y H:i:s')
                                                : 'Not Updated' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST" class="update-status-form">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}">
                                                    @foreach ($statuses as $availableStatus)
                                                        <option value="{{ $availableStatus }}" {{ $row->status === $availableStatus ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $availableStatus)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            @php
                                                $phonescreen = $row->phonescreens->first();
                                            @endphp

                                            @if ($phonescreen && $phonescreen->id)
                                                <button
                                                    type="button"
                                                    class="btn btn-sm my-1"
                                                    style="background-color: #969696; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPhoneScreenModal{{ $phonescreen->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @include('admin.phonescreen.updatemodal', [
                                                    'id' => $phonescreen->id,
                                                    'phonescreen' => $phonescreen,
                                                    'userhrjobs' => $userhrjobss,
                                                ])

                                                <form method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm delete-btn"
                                                            style="background-color: #c03535; color: white;"
                                                            data-url="{{ route('destroyPhoneScreen', $phonescreen->id) }}"
                                                            data-confirm-message="Are you sure you want to delete this phone screen?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-danger">Create a valid phone screen first !</span>
                                            @endif
                                        </td>
                                    @elseif (request('status') === 'reference_check')
                                        <td data-field="reference_check_status">
                                            @php
                                                $totalReferences = count($row->referencechecks); // Jumlah referensi yang telah diisi
                                                $requiredReferences = count($row->user->reference); // Jumlah referensi yang diharapkan dari user
                                            @endphp
                                            {{ $totalReferences }} / {{ $requiredReferences }}&nbsp;

                                            @if ($totalReferences === 0)
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @elseif ($totalReferences === $requiredReferences)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @elseif ($totalReferences < $requiredReferences)
                                                <i class="fas fa-minus-circle text-warning"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif

                                        </td>
                                        {{-- <td data-field="reference_name">{{ $row->referencechecks->first()->reference->reference_name ?? 'No Reference Name' }}</td>
                                        <td data-field="relation">{{ $row->referencechecks->first()->reference->relation ?? 'No Relation' }}</td>
                                        <td data-field="company_name">{{ $row->referencechecks->first()->reference->company_name ?? 'No Company Name' }}</td>
                                        <td data-field="phone">{{ $row->referencechecks->first()->reference->phone ?? 'No Reference Phone' }}</td>
                                        <td data-field="is_call">
                                            @php
                                                $isCall = $row->referencechecks->first()?->reference?->is_call;
                                            @endphp
                                            @if ($isCall === 'yes')
                                                <i class="fas fa-check-circle text-success"></i> <!-- Centang Hijau -->
                                            @elseif ($isCall === 'no' || is_null($isCall))
                                                <i class="fas fa-times-circle text-danger"></i> <!-- Silang Merah -->
                                            @endif
                                        </td>
                                        <td data-field="comment">
                                            @if ($row->referencechecks->first()?->comment)
                                            <span
                                                title="{{ $row->referencechecks->first()->comment }}">
                                                {{ \Illuminate\Support\Str::limit($row->referencechecks->first()->comment, 20, '...') }}
                                            </span>
                                            @else
                                                Not Commented
                                            @endif
                                        </td>
                                        <td data-field="created_at">
                                            {{ $row->referencechecks && $row->referencechecks->first() && $row->referencechecks->first()->created_at
                                                ? $row->referencechecks->first()->created_at->format('d-m-Y H:i:s')
                                                : 'Not Created' }}
                                        </td> --}}
                                        <td data-field="updated_at">
                                            {{ $row->referencechecks && $row->referencechecks->first() && $row->referencechecks->first()->updated_at
                                                ? $row->referencechecks->first()->updated_at->format('d-m-Y H:i:s')
                                                : 'Not Updated' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST" class="update-status-form">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}">
                                                    @foreach ($statuses as $availableStatus)
                                                        <option value="{{ $availableStatus }}" {{ $row->status === $availableStatus ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $availableStatus)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <!-- Tombol View References -->
                                            <button
                                                type="button"
                                                class="btn btn-sm my-1"
                                                style="background-color: #72A28A; color: white;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewReferenceModal{{ $row->id }}">
                                                <i class="fas fa-eye"></i> <!-- Icon untuk melihat -->
                                            </button>

                                            <!-- Include modal dari file terpisah -->
                                            @include('admin.referencecheck.viewmodal', ['row' => $row])
                                        </td>


                                        {{-- <td>
                                            @php
                                                $referencecheck = $row->referencechecks->first();
                                            @endphp

                                            @if ($referencecheck && $referencecheck->id)
                                                <button
                                                    type="button"
                                                    class="btn btn-sm my-1"
                                                    style="background-color: #969696; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editReferenceCheckModal{{ $referencecheck->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @include('admin.referencecheck.updatemodal', [
                                                    'id' => $referencecheck->id,
                                                    'referencecheck' => $referencecheck,
                                                    'userhrjobs' => $userhrjobss,
                                                    'comment' => $referencecheck->comment,
                                                ])

                                                <form method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm delete-btn"
                                                            style="background-color: #c03535; color: white;"
                                                            data-url="{{ route('destroyReferenceCheck', $referencecheck->id) }}"
                                                            data-confirm-message="Are you sure you want to delete this reference check?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-danger">Create a valid reference check first !</span>
                                            @endif
                                        </td> --}}
                                    @elseif (request('status') === 'offering')
                                        <td data-field="offering_file">
                                            @if($row->offerings->isNotEmpty() && $row->offerings->first()->offering_file)
                                                <a href="{{ asset($row->offerings->first()->offering_file) }}" target="_blank">
                                                    View File
                                                </a>
                                            @else
                                                No File
                                            @endif
                                        </td>
                                        <td data-field="created_at">
                                            {{ $row->offerings && $row->offerings->first() && $row->offerings->first()->created_at
                                                ? $row->offerings->first()->created_at->format('d-m-Y H:i:s')
                                                : 'Not Created' }}
                                        </td>
                                        <td data-field="updated_at">
                                            {{ $row->offerings && $row->offerings->first() && $row->offerings->first()->updated_at
                                                ? $row->offerings->first()->updated_at->format('d-m-Y H:i:s')
                                                : 'Not Updated' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST" class="update-status-form">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}">
                                                    @foreach ($statuses as $availableStatus)
                                                        <option value="{{ $availableStatus }}" {{ $row->status === $availableStatus ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $availableStatus)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            @php
                                                $offering = $row->offerings->first();
                                            @endphp

                                            @if ($offering && $offering->id)
                                                <button
                                                    type="button"
                                                    class="btn btn-sm my-1"
                                                    style="background-color: #969696; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editOfferingModal{{ $offering->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @include('admin.offering.updatemodal', [
                                                    'id' => $offering->id,
                                                    'offering' => $offering,
                                                    'userhrjobs' => $userhrjobss,
                                                ])

                                                <form method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm delete-btn"
                                                            style="background-color: #c03535; color: white;"
                                                            data-url="{{ route('destroyOffering', $offering->id) }}"
                                                            data-confirm-message="Are you sure you want to delete this offering?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-danger">Create a valid offering first !</span>
                                            @endif
                                        </td>
                                    @else
                                        <td data-field="salary_expectation" data-salary="{{ $row->salary_expectation }}">Rp {{ number_format($row->salary_expectation, 0, ',', '.') }}</td>
                                        <td data-field="availability">{{ ucwords(str_replace('_', ' ', $row->availability)) }}</td>
                                        <td data-field="created_at">{{ $row->created_at->format('d-m-Y H:i:s') }}</td>
                                        @php
                                            // Mengelompokkan jawaban berdasarkan form dan menghitung total benar, salah, dan rata-rata
                                            $totalsByForm = $row->userAnswer->groupBy('question.form.form_name')->map(function ($answers) {
                                                $totalCorrect = $answers->filter(function ($answer) {
                                                    return $answer->answer->is_answer === 'yes';
                                                })->count();

                                                $totalWrong = $answers->filter(function ($answer) {
                                                    return $answer->answer->is_answer === 'no';
                                                })->count();

                                                $totalAll = $totalCorrect + $totalWrong;

                                                // Menghitung rata-rata per form
                                                $average = $totalAll > 0 ? ($totalCorrect / $totalAll) * 10 : 0;

                                                return [
                                                    'totalCorrect' => $totalCorrect,
                                                    'totalWrong' => $totalWrong,
                                                    'totalAll' => $totalAll,
                                                    'average' => $average
                                                ];
                                            });

                                            // Menghitung rata-rata semua form
                                            $totalAverage = $totalsByForm->pluck('average')->sum();
                                            $totalForms = $totalsByForm->count();
                                            $overallAverage = $totalForms > 0 ? $totalAverage / $totalForms : 0;
                                        @endphp

                                        @if (request('status') === 'applicant')
                                            <!-- Tampilkan rata-rata semua form jika statusnya 'applicant' -->
                                            <td data-field="average">
                                                <span class="text-kaem">{{ number_format($overallAverage, 2) }}</span>
                                            </td>
                                        @else
                                            <td data-field="updated_at">{{ $row->updated_at->format('d-m-Y H:i:s') }}</td>
                                        @endif

                                        <td>
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST" class="update-status-form">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}">
                                                    @foreach ($statuses as $availableStatus)
                                                        <option value="{{ $availableStatus }}" {{ $row->status === $availableStatus ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $availableStatus)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm my-1"
                                                style="background-color: #969696; color: white;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserHrjobModal{{ $row->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            @include('admin.userhrjob.updatemodal', [
                                                'id' => $row->id,
                                                'userhrjob' => $row,
                                                'hrjob' => $hrjobss,
                                                'user' => $userss,
                                            ])

                                            <form method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="btn btn-sm delete-btn"
                                                        style="background-color: #c03535; color: white;"
                                                        data-url="{{ route('destroyUserHrjob', $row->id) }}"
                                                        data-confirm-message="Are you sure you want to delete this user job?">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($userhrjobs as $row)
        <div class="modal fade" id="userDetailsModal-{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel-{{ $row->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userDetailsModalLabel-{{ $row->id }}">User Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($row->user->profile_pict)
                            <img src="{{ asset($row->user->profile_pict) }}" alt="Profile Picture" class="img-fluid profile-user mb-2">
                        @else
                            <p class="mb-2">No Profile Picture</p>
                        @endif
                        <p class="text-kaem mb-0"><strong>{{ $row->user->fullname ?? 'No Fullname' }}</strong> </p>
                        <p class="mb-2"><strong> {{ $row->user->nickname ?? 'No Nickname' }}</strong> </p>
                        <p class="mb-0"><strong>Email:</strong></p>
                        <p class="mb-1"> {{ $row->user->email ?? 'No Email' }}</p>
                        <p class="mb-0"><strong>Phone:</strong></p>
                        <p class="mb-1"> {{ $row->user->phone ?? 'No Phone' }}</p>
                        <p class="mb-0"><strong>Address:</strong></p>
                        <p class="mb-1"> {{ $row->user->address ?? 'No Address' }}</p>
                        <p class="mb-0"><strong>Birth Date / Gender:</strong></p>
                        <p class="mb-1"> {{ $row->user->birth_date ?? 'No Birth Date' }} / {{ $row->user->gender ?? 'No Gender' }}</p>
                        <p class="mb-0"><strong>City:</strong></p>
                        <p class="mb-1"> {{ $row->user->city->city_name ?? 'No City' }}</p>
                    </div>
                    <div class="modal-footer">
                        {{-- @if ($row->user->role === 'applicant')
                        <a href="{{ route('profile.pdf', $row->user->id) }}" class="btn btn-sm" style="background-color: #000; color: white;">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        @endif --}}
                        @if ($row->user->role === 'applicant')
                            <a href="{{ route('profilejob.pdf', ['id' => $row->user->id, 'userhrjob_id' => $row->id]) }}" class="btn btn-sm" style="background-color: #000; color: white;">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        @endif

                        @if ($row->userAnswer->isNotEmpty())
                            <a
                                href="#"
                                class="btn btn-sm"
                                style="background-color: #72A28A; color: white;"
                                data-toggle="modal"
                                data-target="#userAnswerModal-{{ $row->id }}">
                                <i class="fas fa-file"></i> Answers
                            </a>
                        @else
                            <p>No Answers</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- @foreach ($userhrjobs as $row)
        <div class="modal fade" id="userAnswerModal-{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="userAnswerModalLabel-{{ $row->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userAnswerModalLabel-{{ $row->id }}">
                            Answers for {{ $row->hrjob->job_name ?? 'No Job Title' }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @php
                            // Menghitung total jawaban benar dan salah
                            $totalCorrect = $row->userAnswer->filter(function ($answer) {
                                return $answer->answer->is_answer === 'yes';
                            })->count();

                            $totalWrong = $row->userAnswer->filter(function ($answer) {
                                return $answer->answer->is_answer === 'no';
                            })->count();
                        @endphp

                        <p class="modal-subtitlegrey"><strong>Total Correct:</strong> <span class="text-kaem">{{ $totalCorrect }}</span></p>
                        <p class="modal-subtitlegrey"><strong>Total Wrong:</strong> <span class="text-danger">{{ $totalWrong }}</span></p><br>

                        @foreach ($row->userAnswer->groupBy('question.form.form_name') as $formName => $answersByForm)
                            <h6 class="modal-subtitle"><strong>{{ $formName }}</strong></h6>
                            @foreach ($answersByForm as $answer)
                                <div class="mb-3">
                                    <p class="modal-subtitlegrey"><strong>{{ $answer->question->question_name ?? 'No Question' }}</strong></p>
                                    <p class="modal-subtitlegrey"></p>
                                    <ul>
                                        @foreach ($answer->question->answers as $possibleAnswer)
                                            <li class="modal-subtitlegrey {{ $possibleAnswer->is_answer === 'yes' ? 'text-kaem' : '' }}">
                                                {{ $possibleAnswer->answer_name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p class="modal-subtitlegrey">
                                        <strong>Selected:</strong>
                                        <span class="{{ $answer->answer->is_answer === 'yes' ? 'text-kaem' : 'text-danger' }}">
                                            {{ $answer->answer->answer_name ?? 'No Answer Selected' }}
                                        </span>
                                    </p>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}


    @foreach ($userhrjobs as $row)
    <div class="modal fade" id="userAnswerModal-{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="userAnswerModalLabel-{{ $row->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userAnswerModalLabel-{{ $row->id }}">
                        Answers for {{ $row->hrjob->job_name ?? 'No Job Title' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        // Mengelompokkan jawaban berdasarkan form dan menghitung total benar dan salah
                        $totalsByForm = $row->userAnswer->groupBy('question.form.form_name')->map(function ($answers) {
                            $totalCorrect = $answers->filter(function ($answer) {
                                return $answer->answer->is_answer === 'yes';
                            })->count();

                            $totalWrong = $answers->filter(function ($answer) {
                                return $answer->answer->is_answer === 'no';
                            })->count();

                            return [
                                'totalCorrect' => $totalCorrect,
                                'totalWrong' => $totalWrong,
                                'answers' => $answers
                            ];
                        });
                    @endphp

                    @foreach ($totalsByForm as $formName => $data)
                        <h6 class="modal-subtitle"><strong>{{ $formName }}</strong></h6>
                        <p class="modal-subtitlegrey"><strong>Total Correct:</strong> <span class="text-kaem">{{ $data['totalCorrect'] }}</span></p>
                        <p class="modal-subtitlegrey"><strong>Total Wrong:</strong> <span class="text-danger">{{ $data['totalWrong'] }}</span></p>
                        <br>

                        @foreach ($data['answers'] as $answer)
                            <div class="mb-3">
                                <p class="modal-subtitlegrey"><strong>{{ $answer->question->question_name ?? 'No Question' }}</strong></p>
                                <ul>
                                    @foreach ($answer->question->answers as $possibleAnswer)
                                        <li class="modal-subtitlegrey {{ $possibleAnswer->is_answer === 'yes' ? 'text-kaem' : '' }}">
                                            {{ $possibleAnswer->answer_name }}
                                        </li>
                                    @endforeach
                                </ul>
                                <p class="modal-subtitlegrey">
                                    <strong>Selected:</strong>
                                    <span class="{{ $answer->answer->is_answer === 'yes' ? 'text-kaem' : 'text-danger' }}">
                                        {{ $answer->answer->answer_name ?? 'No Answer Selected' }}
                                    </span>
                                </p>
                            </div>
                        @endforeach
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach



@endsection

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    const confirmMessage = this.dataset.confirmMessage || 'Are you sure you want to delete this item?';

                    if (!confirm(confirmMessage)) {
                        return;
                    }

                    const url = this.dataset.url;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    })
                        .then(response => {
                            // Simpan response untuk validasi status
                            const isOk = response.ok;
                            return response.json().then(data => ({ isOk, data }));
                        })
                        .then(({ isOk, data }) => {
                            if (isOk) {
                                const row = this.closest('tr');
                                row.remove(); // Hapus baris
                                showToast('successToast', data.message); // Tampilkan toast sukses
                            } else {
                                showToast('failedToast', data.message); // Tampilkan toast gagal
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('failedToast', 'An error occurred. Please try again.');
                        });
                });
            });

            document.querySelectorAll('#updateRatingForm button[type="submit"]').forEach(button => {
                button.addEventListener('click', function () {
                    // Tambahkan value tombol yang diklik ke dalam form
                    const form = this.closest('form');
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = 'button_action';
                    hiddenField.value = this.value;
                    form.appendChild(hiddenField);
                });
            });

            document.querySelectorAll('#updateUserRatingForm button[type="submit"]').forEach(button => {
                button.addEventListener('click', function () {
                    // Tambahkan value tombol yang diklik ke dalam form
                    const form = this.closest('form');
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = 'button_action';
                    hiddenField.value = this.value;
                    form.appendChild(hiddenField);
                });
            });


            document.querySelectorAll('.update-form').forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const formData = new FormData(this); // Ambil data form
                    const url = this.action; // URL dari atribut `action` pada form
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const formDataObject = Object.fromEntries(formData.entries());

                    formDataObject.interviewers = formData.getAll('interviewers[]');
                    formDataObject.user_interviewers = formData.getAll('user_interviewers[]');

                    // Debug data yang dikirim
                    for (const [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    // Tambahkan interviewers sebagai array kosong jika tidak ada
                    if (!formDataObject.hasOwnProperty('interviewers')) {
                            formDataObject.interviewers = [];
                        }
                    // Tambahkan user_interviewers sebagai array kosong jika tidak ada
                    if (!formDataObject.hasOwnProperty('user_interviewers')) {
                            formDataObject.user_interviewers = [];
                        }


                    fetch(url, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formDataObject),
                    })
                        .then(response => {
                            const isOk = response.ok;
                            return response.json().then(data => ({ isOk, data }));
                        })
                        .then(({ isOk, data }) => {
                            if (isOk) {
                                handleTableRowUpdate(data.updatedRow);
                                console.log('Updated row data:', data.updatedRow);
                                showToast('successToast', data.message);

                                // Tangani modal
                                if (data.modalType === 'hr_interview' || data.modalType === 'user_interview' || data.modalType === 'phone_screen') {
                                    const modalId = data.modalType === 'hr_interview'
                                        ? 'interviewModal'
                                        : data.modalType === 'user_interview'
                                        ? 'userInterviewModal'
                                        : 'phoneScreenModal';

                                    const modalUrl = data.modalType === 'hr_interview'
                                        ? '{{ route("getInterviewModal") }}'
                                        : data.modalType === 'user_interview'
                                        ? '{{ route("getUserInterviewModal") }}'
                                        : '{{ route("getPhoneScreenModal") }}';

                                    loadModal(modalId, modalUrl, (modalElement) => {
                                        const modalInstance = new bootstrap.Modal(modalElement, {
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        // Isi data modal
                                        const modalUserJobName = modalElement.querySelector('#modalUserJobName');
                                        const modalUserJobId = modalElement.querySelector('#modalUserJobId');

                                        if (modalUserJobName) modalUserJobName.value = data.modalData.userJobName;
                                        if (modalUserJobId) modalUserJobId.value = data.modalData.userJobId;

                                        // Inisialisasi Select2
                                        $(modalElement).find('.select2.inside-modal').select2({
                                            dropdownParent: $(modalElement) // Attach dropdown to modal
                                        });

                                        // Inisialisasi Datepicker
                                        $(modalElement).find('.datepicker').datepicker({
                                            format: 'yyyy-mm-dd', // Format tanggal
                                            autoclose: true,
                                        });

                                        // Tampilkan modal
                                        modalInstance.show();
                                    });
                                }

                                // Pastikan interviewers[] diproses sebagai array
                                const interviewers = Array.from(this.querySelectorAll('select[name="interviewers[]"] option:checked'))
                                    .map(option => option.value);
                                // const interviewers = updatedRow.interviewers.map(interviewer => interviewer.name).join(', ');
                                // $(`tr[data-id="${updatedRow.id}"]`).find('[data-field="interviewers"]').html(interviewers);


                                // Hapus key "interviewers" sebelumnya
                                formData.delete('interviewers');

                                // Tambahkan setiap nilai interviewers[] ke dalam FormData
                                interviewers.forEach(interviewer => {
                                    formData.append('interviewers[]', interviewer);
                                });

                                // Pastikan user_interviewers[] diproses sebagai array
                                const user_interviewers = Array.from(this.querySelectorAll('select[name="user_interviewers[]"] option:checked'))
                                    .map(option => option.value);

                                // Hapus key "user_interviewers" sebelumnya
                                formData.delete('user_interviewers');

                                // Tambahkan setiap nilai user_interviewers[] ke dalam FormData
                                user_interviewers.forEach(interviewer => {
                                    formData.append('user_interviewers[]', interviewer);
                                });

                                // Tutup modal jika masih terbuka
                                $(this.closest('.modal')).modal('hide');
                            } else {
                                showToast('failedToast', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error.message || error);
                            showToast('failedToast', 'An error occurred. Please try again.');
                        });
                });
            });

            // Fungsi untuk menangani pembaruan atau penghapusan baris tabel
            function handleTableRowUpdate(updatedRow) {
                console.log('Updating table row:', updatedRow);
                const row = document.querySelector(`tr[data-id="${updatedRow.id}"]`);
                const currentFilterStatus = '{{ $status }}'; // Status filter saat ini (sesuaikan dengan backend Anda)

                if (row) {
                    // Hapus baris jika status tidak sesuai dengan filter saat ini
                    if (updatedRow.status !== currentFilterStatus) {
                        row.remove();
                    } else {
                        // Perbarui tampilan baris jika tetap sesuai filter
                        Object.keys(updatedRow).forEach(key => {
                            const cell = row.querySelector(`[data-field="${key}"]`);
                            if (cell) {
                                // Format khusus jika diperlukan
                                if (key === 'salary_expectation') {
                                    cell.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(updatedRow[key])}`;
                                } else if (key === 'interviewers') {
                                    // Kosongkan isi lama dan tambahkan daftar interviewers baru
                                    cell.innerHTML = '';
                                    if (updatedRow[key] && updatedRow[key].length > 0) {
                                        const ul = document.createElement('ul');
                                        updatedRow[key].forEach(interviewer => {
                                            const li = document.createElement('li');
                                            li.textContent = interviewer.fullname; // Pastikan fullname dikirim dari server
                                            ul.appendChild(li);
                                        });
                                        cell.appendChild(ul);
                                    } else {
                                        cell.textContent = 'No Interviewers';
                                    }
                                } else if (key === 'user_interviewers') {
                                    // Kosongkan isi lama dan tambahkan daftar user_interviewers baru
                                    cell.innerHTML = '';
                                    if (updatedRow[key] && updatedRow[key].length > 0) {
                                        const ul = document.createElement('ul');
                                        updatedRow[key].forEach(interviewer => {
                                            const li = document.createElement('li');
                                            li.textContent = interviewer.fullname; // Pastikan fullname dikirim dari server
                                            ul.appendChild(li);
                                        });
                                        cell.appendChild(ul);
                                    } else {
                                        cell.textContent = 'No User Interviewers';
                                    }
                                } else if (key === "link") {
                                    cell.innerHTML = `<a href="${updatedRow[key]}" target="_blank">${updatedRow[key]}</a>`;
                                } else if (key === 'updated_at') {
                                    cell.textContent = updatedRow[key]; // Format waktu (jika diperlukan)
                                } else {
                                    cell.textContent = updatedRow[key];
                                }
                            }
                        });
                    }
                }
            }

            function loadModal(modalId, modalUrl, callback) {
                fetch(modalUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to load modal content');
                        }
                        return response.text();
                    })
                    .then(html => {
                        // Tambahkan HTML modal ke dalam body
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html.trim();
                        const modalElement = tempDiv.firstElementChild;
                        document.body.appendChild(modalElement);

                        // Jalankan callback jika ada
                        if (typeof callback === 'function') callback(modalElement);
                    })
                    .catch(error => {
                        console.error('Error loading modal:', error);
                        showToast('failedToast', 'Failed to load modal. Please try again.');
                    });
            }

            // Fungsi untuk memperbarui status di tabel
            document.querySelectorAll('.update-status-form select').forEach(select => {
                // Simpan nilai sebelumnya saat select mendapatkan fokus
                select.addEventListener('focus', function() {
                    this.dataset.previousValue = this.value;
                });

                select.addEventListener('change', function () {
                    const previousValue = this.dataset.previousValue;
                    const newValue = this.value;

                    // Jika status baru adalah 'rejected', tampilkan konfirmasi
                    if (newValue === 'rejected') {
                        const confirmed = confirm('Are you sure you want to reject the candidate?');
                        if (!confirmed) {
                            this.value = previousValue; // Kembalikan ke nilai sebelumnya
                            return; // Hentikan proses
                        }
                    }

                    const form = this.closest('form'); // Ambil form terdekat
                    const url = form.action; // URL untuk pengiriman form
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content; // CSRF token
                    const formData = new FormData(form); // Data form yang akan dikirim

                    // Kirim permintaan Ajax dengan Fetch API
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(errorData => {
                                    throw new Error(errorData.message || 'Gagal memperbarui status.');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response:', data);

                            if (data.status === 'success') {
                                // Tangani modal
                                if (data.modalType === 'hr_interview' || data.modalType === 'user_interview' || data.modalType === 'phone_screen') {
                                    const modalId = data.modalType === 'hr_interview'
                                        ? 'interviewModal'
                                        : data.modalType === 'user_interview'
                                            ? 'userInterviewModal'
                                            : 'phoneScreenModal';

                                    const modalUrl = data.modalType === 'hr_interview'
                                        ? '{{ route("getInterviewModal") }}'
                                        : data.modalType === 'user_interview'
                                            ? '{{ route("getUserInterviewModal") }}'
                                            : '{{ route("getPhoneScreenModal") }}';

                                    loadModal(modalId, modalUrl, (modalElement) => {
                                        const modalInstance = new bootstrap.Modal(modalElement, {
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        // Isi data modal
                                        const modalUserJobName = modalElement.querySelector('#modalUserJobName');
                                        const modalUserJobId = modalElement.querySelector('#modalUserJobId');

                                        if (modalUserJobName) modalUserJobName.value = data.modalData.userJobName;
                                        if (modalUserJobId) modalUserJobId.value = data.modalData.userJobId;

                                        // Inisialisasi Select2
                                        $(modalElement).find('.select2.inside-modal').select2({
                                            dropdownParent: $(modalElement) // Attach dropdown ke modal
                                        });

                                        // Inisialisasi Datepicker
                                        $(modalElement).find('.datepicker').datepicker({
                                            format: 'yyyy-mm-dd', // Format tanggal
                                            autoclose: true,
                                        });

                                        // Tampilkan modal
                                        modalInstance.show();
                                    });
                                }

                                // Hapus atau perbarui baris di tabel
                                const row = document.querySelector(`tr[data-id="${data.updatedRow.id}"]`);
                                if (row && data.updatedRow.status !== '{{ $status }}') {
                                    row.remove();
                                } else if (row) {
                                    // Perbarui tampilan row jika status sesuai
                                    row.querySelector('[data-field="status"]').textContent = data.updatedRow.status;
                                    row.querySelector('[data-field="updated_at"]').textContent = data.updatedRow.updated_at;
                                }

                                // Tampilkan toast sukses
                                showToast('successToast', data.message || 'Status berhasil diperbarui!');
                            } else {
                                showToast('failedToast', data.message || 'Gagal memperbarui status.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('failedToast', error.message || 'Terjadi kesalahan. Silakan coba lagi.');
                        });
                });
            });

            document.querySelectorAll('.update-form-offering').forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const formData = new FormData(form); // Ambil semua input dalam form
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    // Debugging: Periksa isi FormData
                    for (const [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    fetch(form.action, {
                        method: 'POST', // Gunakan POST untuk kompatibilitas dengan FormData
                        headers: {
                            'X-CSRF-TOKEN': csrfToken, // Tambahkan token CSRF
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData, // Kirim FormData (termasuk file)
                    })
                        .then(response => {
                            const isOk = response.ok;
                            return response.json().then(data => ({ isOk, data }));
                        })
                        .then(({ isOk, data }) => {
                            console.log('Server Response:', data);
                            if (isOk) {
                                updateTableOfferingRow(data.updatedRow);
                                showToast('successToast', data.message);
                                // Tutup modal jika ada
                                if (form.closest('.modal')) {
                                    $(form.closest('.modal')).modal('hide');
                                }
                            } else {
                                showToast('failedToast', data.message || 'Failed to update offering.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('failedToast', 'An error occurred. Please try again.');
                        });
                });
            });

                function updateTableOfferingRow(updatedRow) {
                    const row = document.querySelector(`tr[data-id="${updatedRow.id}"]`);
                    if (row) {
                        Object.keys(updatedRow).forEach(key => {
                            const cell = row.querySelector(`[data-field="${key}"]`);
                            if (cell) {
                                cell.textContent = updatedRow[key];
                            }
                        });
                    }
                }

            function showToast(toastId, message) {
                const toastEl = document.getElementById(toastId);
                if (toastEl) {
                    toastEl.querySelector('.toast-body').textContent = message;
                    toastEl.classList.add('show'); // Tambahkan kelas 'show'
                    toastEl.style.pointerEvents = 'auto'; // Aktifkan pointer-events

                    setTimeout(() => {
                        toastEl.classList.remove('show'); // Hilangkan kelas 'show'
                        toastEl.style.pointerEvents = 'none'; // Matikan pointer-events
                    }, 3000); // Hilangkan setelah 3 detik
                } else {
                    console.error(`Toast element with ID ${toastId} not found`);
                }
            }

            @if(session('toast_type') && session('toast_message'))
                const toastId = "{{ session('toast_type') === 'success' ? 'successToast' : 'failedToast' }}";
                const message = "{{ session('toast_message') }}";
                showToast(toastId, message);
            @endif

        // Pilih semua checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.select-job');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Kirim data yang dipilih
        document.getElementById('reject-selected').addEventListener('click', function () {
            // Ambil semua job yang dipilih
            const selectedJobs = Array.from(document.querySelectorAll('.select-job:checked')).map(
                checkbox => checkbox.value
            );

            // Jika tidak ada job yang dipilih, tampilkan pesan dan hentikan proses
            if (selectedJobs.length === 0) {
                alert('No jobs selected!');
                return;
            }

            // Tampilkan popup konfirmasi
            const confirmed = confirm('Are you sure you want to reject the selected jobs?');
            if (!confirmed) {
                return; // Hentikan proses jika pengguna membatalkan
            }

            // Kirim permintaan ke server untuk menolak job yang dipilih
            fetch('{{ route('bulkRejectStatus') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ selected_jobs: selectedJobs }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tampilkan toast sukses
                        showToast('successToast', data.message);

                        // Hapus baris dari tabel secara dinamis
                        selectedJobs.forEach(jobId => {
                            const row = document.querySelector(`tr[data-id="${jobId}"]`);
                            if (row) {
                                row.remove(); // Hapus baris dari tabel
                            }
                        });
                    } else {
                        // Tampilkan toast gagal
                        showToast('failedToast', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('failedToast', 'An unexpected error occurred. Please try again.');
                });
        });

        $.extend(true, $.fn.dataTable.defaults, {
            order: [[1, 'desc']] // Default descending pada kolom pertama untuk semua tabel
        });

    });

</script>
