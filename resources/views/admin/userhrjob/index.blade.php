@extends('admin.layout.app')
@section('title', 'User Job')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">User Job</h1>

    @if (session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{session('error')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <p class="mb-3">Applicant's main data to the job</p>

    <!-- Topbar for Filtering by Status -->
    <div class="mb-3">
        <ul class="nav nav-pills nav-justified custom-nav">
            @foreach ($statuses as $filterStatus)
                <li class="nav-item">
                    <a href="{{ route('getUserHrjob', ['status' => $filterStatus]) }}"
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
            @if (request('status') === 'hr_interview')
            <a href="{{ route('addUserHrjobInterview') }}" class="btn btn-sm" style="background-color: #72A28A; color: white;">
                <i class="fas fa-plus"></i> Add
            </a>
            @else
            <a href="{{ route('addUserHrjob') }}" class="btn btn-sm" style="background-color: #72A28A; color: white;">
                <i class="fas fa-plus"></i> Add
            </a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="fa-sm text-center">
                            <th>ID</th>
                            <th>Job</th>
                            <th>Applicant</th>
                            <th>Status</th>
                            @if (request('status') === 'hr_interview')
                                <th>Interviewer</th>
                                <th>Interview Date</th>
                                <th>Time</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Location</th>
                                <th>Link</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            @else
                                <th>Salary Expectation</th>
                                <th>Availability</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userhrjobs as $row)
                            @if (request('status') === null || request('status') === $row->status)
                                <tr class="fa-sm">
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->hrjob->job_name ?? 'No Job' }}</td>
                                    <td>{{ $row->user->fullname ?? 'No Applicant' }}</td>
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
                                            {{ $row->interviews->first()->rating ?? 'Not Rated' }}
                                        </td>
                                        <td>
                                            {{ $row->interviews->first()->comment ?? 'Not Commented' }}
                                        </td>
                                        <td>
                                            {{ $row->interviews->first()->location ?? 'Not Located' }}
                                        </td>
                                        <td>
                                            {{ $row->interviews->first()->comment ?? 'No Link' }}
                                        </td>
                                        <td>{{ $row->interviews->first()->created_at ?? 'Not Created' }}</td>
                                        <td>{{ $row->interviews->first()->updated_at ?? 'Not Updated' }}</td>
                                        <td>
                                            @php
                                                $interview = $row->interviews->first();
                                            @endphp

                                            @if ($interview && $interview->id)
                                                <a href="{{ route('editUserHrjobInterview', $interview->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="{{ route('editUserHrjobRating', $interview->id) }}" class="btn btn-sm my-1" style="background-color: #FFA500; color: white;">
                                                    <i class="fas fa-star"></i> Rating
                                                </a>
                                                <form action="{{ route('destroyUserHrjobInterview', $interview->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this interview?')">
                                                        <i class="fas fa-trash"></i> Delete
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
                                            <a href="{{ route('editUserHrjob', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;"><i class="fas fa-edit"></i> Edit</a>
                                            <form action="{{ route('destroyUserHrjob', $row->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this user job?')"><i class="fas fa-trash"></i> Delete</button>
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

                            <input type="hidden" name="redirectTo" value="{{ session('redirectTo') }}">

                            <div class="form-group">
                                <label>Applicant</label>
                                    <input name="id_user_job" class="form-control" value="{{ session('userJobId') }}" readonly>
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


@endsection
