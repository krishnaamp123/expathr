@extends('admin.layout.app')
@section('title', 'User Interview')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">User Interview</h1>

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

    <p class="mb-3">Pairing users with applicants for interviews and providing assessments of interview results</p>

    <div class="mb-4">
        <ul class="nav nav-pills custom-nav">
            <li class="nav-item">
                <a href="{{ route('getInterview') }}"
                   class="nav-link pl-5 pr-5 {{ request()->routeIs('getInterview') ? 'active' : '' }}">
                   HR Interviews
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('getUserInterview') }}"
                   class="nav-link pl-5 pr-5 {{ request()->routeIs('getUserInterview') ? 'active' : '' }}">
                   User Interviews
                </a>
            </li>
        </ul>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div>
                <button
                    type="button"
                    class="btn btn-sm mr-1"
                    style="background-color: #72A28A; color: white;"
                    data-bs-toggle="modal"
                    data-bs-target="#addUserInterviewModal">
                    <i class="fas fa-plus"></i> Add
                </button>

                @include('admin.userinterview.storemodal', [
                    'userinterview' => $userinterviews,
                    'userhrjobs' => $userhrjobs,
                    'users' => $users,
                ])
                <a href="{{ route('exportUserInterview') }}" class="btn btn-sm mr-1" style="background-color: #000; color: white;">
                    <i class="fas fa-file-excel"></i> Export All
                </a>
                <!-- Tombol Export -->
                <button class="btn btn-sm mr-1" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
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
            <div>
                <form action="{{ route('getUserInterview') }}" method="GET" class="form-inline">
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
                    <a href="{{ route('getUserInterview') }}" class="btn btn-secondary btn-sm mb-2 mr-2">Clear Date</a>
                    <button type="submit" class="btn btn-primary btn-sm mb-2">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="small text-center">
                            <th>ID</th>
                            <th>Job</th>
                            <th>Applicant</th>
                            <th>Interviewer</th>
                            <th>Interview Date</th>
                            <th>Interview Time</th>
                            <th>Location</th>
                            <th>Link</th>
                            <th>Confirm Attendance</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userinterviews as $row)
                        <tr class="small">
                            <td>{{$row->id}}</td>
                            <td>{{$row->userHrjob->hrjob->job_name ?? 'No Applicant'}}</td>
                            <td>{{$row->userHrjob->user->fullname ?? 'No Applicant'}}</td>
                            <td>
                                @if ($row->user_interviewers->isNotEmpty())
                                    <ul>
                                        @foreach ($row->user_interviewers as $interviewer)
                                            <li>{{ $interviewer->fullname }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span>No Interviewers</span>
                                @endif
                            </td>
                            <td>{{$row->interview_date ?? 'No Date'}}</td>
                            <td>{{$row->time ?? 'No Time'}}</td>
                            <td>{{$row->location ?? 'No Location'}}</td>
                            <td>
                                @if ($row->link)
                                    <a href="{{ $row->link }}" target="_blank" title="{{ $row->link }}">
                                        {{ $row->link }}
                                    </a>
                                @else
                                    No Link
                                @endif
                            </td>
                            <td>{{$row->arrival ?? 'No Arrival'}}</td>
                            <td>{{$row->created_at}}</td>
                            <td>{{$row->updated_at}}</td>
                            <td>{{$row->rating ?? 'No Rating'}}</td>
                            <td>{{$row->comment ?? 'No Comment'}}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm my-1"
                                    style="background-color: #969696; color: white;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserInterviewModal{{ $row->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                @include('admin.userinterview.updatemodal', [
                                    'id' => $row->id,
                                    'userinterview' => $row,
                                    'userhrjobs' => $userhrjobs,
                                    'users' => $users,
                                ])

                                <button
                                    type="button"
                                    class="btn btn-sm my-1"
                                    style="background-color: #FFA500; color: white;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserRatingModal{{ $row->id }}">
                                    <i class="fas fa-star"></i>
                                </button>

                                <!-- Include Modal -->
                                @include('admin.userinterview.ratingmodal', [
                                    'id' => $row->id,
                                    'rating' => $row->rating,
                                    'comment' => $row->comment
                                ])

                                <form action="{{ route('destroyUserInterview', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this user interview?')"><i class="fas fa-trash"></i>
                                        {{-- Delete --}}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
});

</script>
