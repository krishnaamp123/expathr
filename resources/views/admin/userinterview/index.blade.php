@extends('admin.layout.app')
@section('title', 'User Interview')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">User Interview</h1>

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
        <div class="card-header py-3">
            <a href="{{route('addUserInterview')}}" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
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
                        @foreach ($userinterviews as $row)
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
                                <a href="{{ route('editUserInterview', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('editUserRating', $row->id) }}" class="btn btn-sm my-1" style="background-color: #FFA500; color: white;"><i class="fas fa-star"></i> Rating</a>
                                <form action="{{ route('destroyUserInterview', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm my-1" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this user interview?')"><i class="fas fa-trash"></i> Delete</button>
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
