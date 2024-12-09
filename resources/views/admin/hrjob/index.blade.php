@extends('admin.layout.app')
@section('title', 'Job')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Job</h1>

    @if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <p class="mb-3">Master data job</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{route('addHrjob')}}" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="fa-sm text-center">
                            <th>ID</th>>
                            <th>Job Category</th>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            <th>Job Report</th>
                            <th>Salary</th>
                            <th>Description</th>
                            <th>Qualification</th>
                            <th>Location Type</th>
                            <th>Location</th>
                            <th>Experience Min</th>
                            <th>Education Min</th>
                            <th>Expired</th>
                            <th>Number Hired</th>
                            <th>Is Active</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hrjobs as $row)
                        <tr class="fa-sm">
                            <td>{{$row->id}}</td>
                            <td>{{$row->category->category_name ?? 'No Category'}}</td>
                            <td>{{$row->job_name}}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $row->job_type)) }}</td>
                            <td>{{$row->job_report}}</td>
                            <td data-job="{{ $row->price }}">Rp {{ number_format($row->price, 0, ',', '.') }}</td>
                            <td>{{$row->description}}</td>
                            <td>{{$row->qualification}}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $row->location_type)) }}</td>
                            <td>{{$row->city->city_name ?? 'No City'}}</td>
                            <td>{{$row->experience_min}}</td>
                            <td>{{$row->education_min}}</td>
                            <td>{{$row->expired}}</td>
                            <td>{{$row->number_hired}}</td>
                            <td>{{$row->is_active}}</td>
                            <td>{{$row->created_at}}</td>
                            <td>{{$row->updated_at}}</td>
                            <td>
                                <a href="{{ route('editHrjob', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('destroyHrjob', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this job?')">
                                        <i class="fas fa-trash"></i> Delete
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
