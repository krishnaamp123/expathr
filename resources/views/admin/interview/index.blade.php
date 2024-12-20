@extends('admin.layout.app')
@section('title', 'Interview')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Interview</h1>

    @if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('message')}}
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

    <p class="mb-3">Pairing recruiters with applicants for interviews and providing assessments of interview results</p>

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
                <a href="{{route('addInterview')}}" class="btn btn-sm mr-1" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
                <a href="{{ route('exportInterview') }}" class="btn btn-sm mr-1" style="background-color: #000; color: white;">
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
            </div>
            <div>
                <form action="{{ route('getInterview') }}" method="GET" class="form-inline">
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
                    <a href="{{ route('getInterview') }}" class="btn btn-secondary btn-sm mb-2 mr-2">Clear Date</a>
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
                            <th>Date</th>
                            <th>Time</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Location</th>
                            <th>Link</th>
                            <th>Confirm Attendance</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($interviews as $row)
                        <tr class="small">
                            <td>{{$row->id}}</td>
                            <td>{{$row->userHrjob->hrjob->job_name ?? 'No Applicant'}}</td>
                            <td>{{$row->userHrjob->user->fullname ?? 'No Applicant'}}</td>
                            <td>{{$row->user->fullname ?? 'No Interviewer'}}</td>
                            <td>{{$row->interview_date ?? 'No Date'}}</td>
                            <td>{{$row->time ?? 'No Time'}}</td>
                            <td>{{$row->rating ?? 'No Rating'}}</td>
                            <td>{{$row->comment ?? 'No Comment'}}</td>
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
                            <td>
                                <a href="{{ route('editInterview', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;"><i class="fas fa-edit"></i>
                                    {{-- Edit --}}
                                </a>
                                <a href="{{ route('editRating', $row->id) }}" class="btn btn-sm my-1" style="background-color: #FFA500; color: white;"><i class="fas fa-star"></i>
                                    {{-- Rating --}}
                                </a>
                                <form action="{{ route('destroyInterview', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this interview?')"><i class="fas fa-trash"></i>
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
