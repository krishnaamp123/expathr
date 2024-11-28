@extends('admin.layout.app')
@section('title', 'User')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">User</h1>

    @if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <p class="mb-3">Master data user</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{route('addUser')}}" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="fa-sm text-center">
                            <th>ID</th>
                            <th>Profile</th>
                            <th>City</th>
                            <th>Employee ID</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Fullname</th>
                            <th>Nickname</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Birth Date</th>
                            <th>Gender</th>
                            <th>Role</th>
                            <th>Email Verified</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $row)
                        <tr class="fa-sm">
                            <td>{{$row->id}}</td>
                            <td class="text-center">
                                @if ($row->profile_pict)
                                    <img src="{{ asset($row->profile_pict) }}" alt="Profile Image" class="profile-user">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{$row->city->city_name ?? 'No City'}}</td>
                            <td>{{$row->employee_id ?? 'Applicant'}}</td>
                            <td>{{$row->email}}</td>
                            <td>{{$row->password}}</td>
                            <td>{{$row->fullname}}</td>
                            <td>{{$row->nickname}}</td>
                            <td>{{$row->phone}}</td>
                            <td>{{$row->address}}</td>
                            <td>{{$row->birth_date}}</td>
                            <td>{{$row->gender}}</td>
                            <td>{{$row->role}}</td>
                            <td>{{$row->email_verified_at}}</td>
                            <td>{{$row->created_at}}</td>
                            <td>{{$row->updated_at}}</td>
                            <td>
                                <a href="{{ route('editUser', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('destroyUser', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                <a href="{{ route('profile.pdf', $row->id) }}" class="btn btn-sm my-1" style="background-color: #4CAF50; color: white;">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
