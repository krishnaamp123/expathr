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
                        <tr class="small text-center">
                            <th>ID</th>
                            <th>Job Category</th>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            {{-- <th>Job Report</th>
                            <th>Salary</th>
                            <th>Description</th>
                            <th>Qualification</th>
                            <th>Location Type</th> --}}
                            <th>Location</th>
                            <th>Placement</th>
                            {{-- <th>Experience Min</th>
                            <th>Education Min</th>
                            <th>Number Hired</th> --}}
                            <th>Is Active</th>
                            <th>Is Ended</th>
                            {{-- <th>Hiring Cost</th> --}}
                            <th>Created At</th>
                            <th>Expired</th>
                            <th>Job Closed</th>
                            {{-- <th>Updated At</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hrjobs as $row)
                        <tr class="small">
                            <td>{{$row->id}}</td>
                            <td>{{$row->category->category_name ?? 'No Category'}}</td>
                            <td>{{$row->job_name}}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $row->job_type)) }}</td>
                            {{-- <td>{{$row->job_report}}</td>
                            <td data-job="{{ $row->price }}">Rp {{ number_format($row->price, 0, ',', '.') }}</td>
                            <td>{{$row->description}}</td>
                            <td>{{$row->qualification}}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $row->location_type)) }}</td> --}}
                            <td>{{$row->city->city_name ?? 'No City'}}</td>
                            <td>{{$row->outlet->outlet_name ?? 'No Outlet'}}</td>
                            {{-- <td>{{$row->experience_min}}</td>
                            <td>{{$row->education_min}}</td>
                            <td>{{$row->number_hired}}</td> --}}
                            <td>{{$row->is_active}}</td>
                            <td>{{$row->is_ended}}</td>
                            {{-- <td>{{$row->hiring_cost ?? 'No Cost'}}</td> --}}
                            <td>{{$row->created_at}}</td>
                            <td>{{$row->expired}}</td>
                            <td>{{$row->job_closed ?? 'Not Closed'}}</td>
                            {{-- <td>{{$row->updated_at}}</td> --}}
                            <td>
                                <a href="{{ route('editHrjob', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;">
                                    <i class="fas fa-edit"></i>
                                    {{-- Edit --}}
                                </a>
                                <button
                                    class="btn btn-sm my-1 update-is-ended"
                                    data-id="{{ $row->id }}"
                                    data-is-ended="{{ $row->is_ended }}"
                                    style="background-color: #72A28A; color: white;">
                                    <i class="fas fa-tag"></i>
                                </button>
                                <form action="{{ route('destroyHrjob', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this job?')">
                                        <i class="fas fa-trash"></i>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = $('#updateIsEndedModal');
            const form = $('#updateIsEndedForm');

            $('.update-is-ended').on('click', function () {
                const jobId = $(this).data('id');
                const isEnded = $(this).data('is-ended');

                if (isEnded === 'yes') {
                    alert('This job is already ended!');
                    return;
                }

                form.attr('action', `/job/update-is-ended/${jobId}`);
                modal.modal('show');
            });
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="updateIsEndedModal" tabindex="-1" aria-labelledby="updateIsEndedLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateIsEndedForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateIsEndedLabel">Update Job Ended</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="is_ended" value="yes">
                        <div class="form-group">
                            <label for="hiring_cost">Hiring Cost</label>
                            <input type="number" id="hiring_cost" name="hiring_cost" class="form-control"required>
                            @error('hiring_cost')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
