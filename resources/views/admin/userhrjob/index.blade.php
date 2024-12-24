@extends('admin.layout.app')
@section('title', 'User Job')
@section('content')

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">User Job</h1>

    <!-- Toast Container -->
    <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <!-- Toast -->
        @if(session('success'))
        <div id="successToast" class="toast align-items-center text-white font-weight-bold" style="background-color: #72A28A;" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(session('failed'))
        <div id="failedToast" class="toast align-items-center text-white font-weight-bold" style="background-color: #c03535;" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('failed') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>

    <p class="mb-3">Applicant's main data to the job</p>

    <!-- Topbar for Filtering by Status -->
    <div class="mb-3">
        <ul class="nav nav-pills nav-justified custom-nav">
            @foreach ($statuses as $filterStatus)
                <li class="nav-item">
                    <a href="{{ route('getUserHrjob', ['status' => $filterStatus, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
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
                                            <label for="start_date" class="mb-0"><strong>Start Date:</strong></label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-0"><strong>End Date:</strong></label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" required>
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
                                            <label for="start_date" class="mb-0"><strong>Start Date:</strong></label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date" class="mb-0"><strong>End Date:</strong></label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" required>
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

                    <button id="reject-selected" class="btn btn-danger btn-sm">Reject Selected</button>
                    @endif
                </div>
            <!-- Form Filter Tanggal -->
            <form action="{{ route('getUserHrjob') }}" method="GET" class="form-inline">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="form-group mx-sm-2 mb-2">
                    <label for="start_date" class="sr-only">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date">
                </div>
                <span class="mx-2">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <div class="form-group mx-sm-2 mb-2">
                    <label for="end_date" class="sr-only">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="End Date">
                </div>
                <a href="{{ route('getUserHrjob', ['status' => request('status')]) }}" class="btn btn-secondary btn-sm mb-2 mr-2">Clear Date</a>
                <button type="submit" class="btn btn-primary btn-sm mb-2">Filter</button>
            </form>
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
                                <th>Confirm Attendance</th>
                                {{-- <th>Created At</th> --}}
                                <th>Updated At</th>
                                <th>Rating</th>
                                <th>Comment</th>
                            @elseif (request('status') === 'user_interview')
                                <th>Interviewer</th>
                                <th>Interview Date</th>
                                <th>Interview Time</th>
                                <th>Location</th>
                                <th>Link</th>
                                <th>Confirm Attendance</th>
                                {{-- <th>Created At</th> --}}
                                <th>Updated At</th>
                                <th>Rating</th>
                                <th>Comment</th>
                            @else
                                <th>Salary Expectation</th>
                                <th>Availability</th>
                                <th>Applied At</th>
                                <th>Updated At</th>
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
                                    <td>{{ $row->hrjob->job_name ?? 'No Job' }}</td>
                                    <td>
                                        <a
                                            href="#"
                                            class="btn p-0"
                                            style="font-size: 0.85rem;"
                                            data-toggle="modal"
                                            data-target="#userDetailsModal-{{ $row->id }}">
                                            {{ \Illuminate\Support\Str::limit($row->user->fullname ?? 'No Applicant', 20, '...') }}
                                        </a>
                                    </td>
                                    <!-- Tampilkan Interviewer dan Interview Date jika status adalah hr_interview -->
                                    @if (request('status') === 'hr_interview')
                                        <td>
                                            {{ $row->interviews->first()->user->fullname ?? 'Not Assigned' }}
                                        </td>
                                        <td>
                                            {{ $row->interviews->first()->interview_date ?? 'Not Scheduled' }}
                                        </td>
                                        <td>
                                            {{ $row->interviews->first()->time ?? 'Not Timed' }}
                                        </td>
                                        <td>
                                            {{ $row->interviews->first()->location ?? 'Not Located' }}
                                        </td>
                                        <td>
                                            @if ($row->interviews->first()?->link)
                                                <a href="{{ $row->interviews->first()->link }}" target="_blank" title="{{ $row->interviews->first()->link }}">
                                                    {{ \Illuminate\Support\Str::limit($row->interviews->first()->link, 20, '...') }}
                                                </a>
                                            @else
                                                No Link
                                            @endif
                                        </td>
                                        <td>{{ $row->interviews->first()->arrival ?? 'Not Confirmed' }}</td>
                                        {{-- <td>{{ $row->interviews->first()->created_at ?? 'Not Created' }}</td> --}}
                                        <td>{{ $row->interviews->first()->updated_at ?? 'Not Updated' }}</td>
                                        <td>
                                            {{ $row->interviews->first()->rating ?? 'Not Rated' }}
                                        </td>
                                        <td>
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
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}" onchange="this.form.submit()">
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
                                                    'comment' => $interview->comment
                                                ])
                                                <form action="{{ route('destroyInterview', $interview->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this interview?')">
                                                        <i class="fas fa-trash"></i>
                                                        {{-- Delete --}}
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-danger">Create a valid interview first !</span>
                                            @endif
                                        </td>
                                    @elseif (request('status') === 'user_interview')
                                        <td>
                                            {{ $row->userinterviews->first()->user->fullname ?? 'Not Assigned' }}
                                        </td>
                                        <td>
                                            {{ $row->userinterviews->first()->interview_date ?? 'Not Scheduled' }}
                                        </td>
                                        <td>
                                            {{ $row->userinterviews->first()->time ?? 'Not Timed' }}
                                        </td>
                                        <td>
                                            {{ $row->userinterviews->first()->location ?? 'Not Located' }}
                                        </td>
                                        <td>
                                            @if ($row->userinterviews->first()?->link)
                                                <a href="{{ $row->userinterviews->first()->link }}" target="_blank" title="{{ $row->userinterviews->first()->link }}">
                                                    {{ \Illuminate\Support\Str::limit($row->userinterviews->first()->link, 20, '...') }}
                                                </a>
                                            @else
                                                No Link
                                            @endif
                                        </td>
                                        <td>{{ $row->userinterviews->first()->arrival ?? 'Not Confirmed' }}</td>
                                        {{-- <td>{{ $row->userinterviews->first()->created_at ?? 'Not Created' }}</td> --}}
                                        <td>{{ $row->userinterviews->first()->updated_at ?? 'Not Updated' }}</td>
                                        <td>
                                            {{ $row->userinterviews->first()->rating ?? 'Not Rated' }}
                                        </td>
                                        <td>
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
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}" onchange="this.form.submit()">
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
                                                    'comment' => $userinterview->comment
                                                ])
                                                <form action="{{ route('destroyUserInterview', $userinterview->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this interview?')">
                                                        <i class="fas fa-trash"></i>
                                                        {{-- Delete --}}
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-danger">Create a valid interview first !</span>
                                            @endif
                                        </td>
                                    @else
                                        <td data-salary="{{ $row->salary_expectation }}">Rp {{ number_format($row->salary_expectation, 0, ',', '.') }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $row->availability)) }}</td>
                                        <td>{{ $row->created_at }}</td>
                                        <td>{{ $row->updated_at }}</td>
                                        <td>
                                            <form action="{{ route('updateStatus', $row->id) }}" method="POST">
                                                @csrf
                                                <select name="status" class="form-control form-control-sm" data-id="{{ $row->id }}" onchange="this.form.submit()">
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
                                            <form action="{{ route('destroyUserHrjob', $row->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this user job?')"><i class="fas fa-trash"></i>
                                                    {{-- Delete --}}
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
    <!-- Modal -->
    @if (session('showModal'))
        <div class="modal fade show" id="interviewModal" tabindex="-1" role="dialog" aria-labelledby="interviewModalLabel" aria-hidden="true" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('storeInterview') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="interviewModalLabel">Add Interview Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='{{ route('getUserHrjob') }}'">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label>Applicant</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ session('userJobName') ?? 'N/A' }}"
                                    readonly
                                >
                                <input
                                    type="hidden"
                                    name="id_user_job"
                                    value="{{ session('userJobId') }}"
                                >
                            </div>

                                <div class="form-group">
                                    <label>Interviewer</label>
                                    <select name="id_user" class="form-control select2">
                                        <option value="">Select Interviewer</option>
                                        @foreach($users as $interviewer)
                                            <option value="{{ $interviewer->id }}">{{ $interviewer->fullname }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_user')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Interview Date</label>
                                    <input type="text" name="interview_date" class="form-control datepicker datepicker-input">
                                    @error('interview_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" name="time" class="form-control">
                                    @error('time')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="location" class="form-control">
                                    @error('location')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Link</label>
                                    <input type="text" name="link" class="form-control">
                                    @error('link')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('getUserHrjob') }}'">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @elseif (session('showuserModal'))
        <div class="modal fade show" id="interviewuserModal" tabindex="-1" role="dialog" aria-labelledby="interviewuserModalLabel" aria-hidden="true" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('storeUserInterview') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="interviewuserModalLabel">Add User Interview Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='{{ route('getUserHrjob') }}'">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label>Applicant</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ session('userJobName') ?? 'N/A' }}"
                                    readonly
                                >
                                <input
                                    type="hidden"
                                    name="id_user_job"
                                    value="{{ session('userJobId') }}"
                                >
                            </div>

                                <div class="form-group">
                                    <label>Interviewer</label>
                                    <select name="id_user" class="form-control select2">
                                        <option value="">Select Interviewer</option>
                                        @foreach($users as $interviewer)
                                            <option value="{{ $interviewer->id }}">{{ $interviewer->fullname }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_user')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Interview Date</label>
                                    <input type="text" name="interview_date" class="form-control datepicker datepicker-input">
                                    @error('interview_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" name="time" class="form-control">
                                    @error('time')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="location" class="form-control">
                                    @error('location')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Link</label>
                                    <input type="text" name="link" class="form-control">
                                    @error('link')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('getUserHrjob') }}'">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if ($row->user->role === 'applicant')
                        <a href="{{ route('profile.pdf', $row->user->id) }}" class="btn my-1" style="background-color: #000; color: white;">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        @endif
                        @if ($row->answers->isNotEmpty())
                            <a
                                href="#"
                                class="btn my-1"
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

    <!-- Definisikan Pemetaan Jawaban -->
    @php
    $answerLabels = [
        1 => 'Sangat Tidak Baik',
        2 => 'Tidak Baik',
        3 => 'Netral',
        4 => 'Baik',
        5 => 'Sangat Baik',
    ];
    @endphp

    @foreach ($userhrjobs as $row)
        @if ($row->answers->isNotEmpty())
            <div class="modal fade" id="userAnswerModal-{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="userAnswerModalLabel-{{ $row->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userAnswerModalLabel-{{ $row->id }}">Answers</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @foreach ($row->answers as $answer)
                                <div class="mb-4">
                                    <p class="mb-1"> <strong>{{ $answer->form->question->question ?? 'No Question' }}</strong></p>
                                    <p class="mb-1"> {{ $answerLabels[$answer->answer] ?? 'No Answer' }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
    // Simpan posisi scroll sebelum reload
        const scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, parseInt(scrollPosition, 10));
            sessionStorage.removeItem('scrollPosition');
        }

        // Simpan posisi scroll saat form dikirim
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                sessionStorage.setItem('scrollPosition', window.scrollY);
            });
        });

        // Inisialisasi toast
        ['successToast', 'failedToast'].forEach(id => {
            const toastEl = document.getElementById(id);
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                toast.show();
            }
        });

        // Pilih semua checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.select-job');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Kirim data yang dipilih
        document.getElementById('reject-selected').addEventListener('click', function () {
            const selectedJobs = Array.from(document.querySelectorAll('.select-job:checked')).map(
                checkbox => checkbox.value
            );

            if (selectedJobs.length === 0) {
                alert('No jobs selected!');
                return;
            }

            if (!confirm('Are you sure you want to reject the selected jobs?')) {
                return;
            }

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

                        // Refresh halaman untuk menampilkan data yang diperbarui
                        setTimeout(() => {
                            location.reload();
                        }); // Berikan waktu untuk toast sebelum reload
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

        // Fungsi untuk menampilkan toast
        function showToast(toastId, message) {
            const toastEl = document.getElementById(toastId);
            if (toastEl) {
                // Masukkan pesan ke dalam toast
                toastEl.querySelector('.toast-body').textContent = message;

                const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                toast.show();
            } else {
                console.error(`Toast element with ID "${toastId}" not found.`);
            }
        }

    });

</script>
