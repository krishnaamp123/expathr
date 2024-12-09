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

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{route('addInterview')}}" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="fa-sm text-center">
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
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($interviews as $row)
                        <tr class="fa-sm">
                            <td>{{$row->id}}</td>
                            <td>{{$row->userHrjob->hrjob->job_name ?? 'No Applicant'}}</td>
                            <td>{{$row->userHrjob->user->fullname ?? 'No Applicant'}}</td>
                            <td>{{$row->user->fullname ?? 'No Interviewer'}}</td>
                            <td>{{$row->interview_date}}</td>
                            <td>{{$row->time}}</td>
                            <td>{{$row->rating ?? 'No Rating'}}</td>
                            <td>{{$row->comment ?? 'No Comment'}}</td>
                            <td>{{$row->location ?? 'No Location'}}</td>
                            <td>{{$row->link ?? 'No Link'}}</td>
                            <td>{{$row->created_at}}</td>
                            <td>{{$row->updated_at}}</td>
                            <td>
                                <a href="{{ route('editInterview', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('editRating', $row->id) }}" class="btn btn-sm my-1" style="background-color: #FFA500; color: white;"><i class="fas fa-star"></i> Rating</a>
                                <form action="{{ route('destroyInterview', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this interview?')"><i class="fas fa-trash"></i> Delete</button>
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
