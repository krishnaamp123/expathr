@extends('admin.layout.app')
@section('title', 'Edit User')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">User</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update User Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateUser', $user->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Current Image</label><br>
                            @if ($user->profile_pict)
                            <img src="{{ asset($user->profile_pict) }}" alt="Profile Image" class="profile-user">
                            @else
                                <span>No Image Available</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>New Image</label>
                            <input type="file" name="file" class="form-control">
                            @error('file')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>City</label>
                            <select name="id_city" class="form-control select2">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $city->id == $user->id_city ? 'selected' : '' }}>
                                        {{ $city->city_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Employee ID</label>
                            <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $user->employee_id) }}">
                            @error('employee_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Fullname</label>
                            <input type="text" name="fullname" class="form-control" value="{{ old('fullname', $user->fullname) }}">
                            @error('fullname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nickname</label>
                            <input type="text" name="nickname" class="form-control" value="{{ old('nickname', $user->nickname) }}">
                            @error('nickname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Phone</label>
                            <input type="number" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="birth_date">Birth Date</label>
                            <input type="text" class="form-control datepicker datepicker-input" id="birth_date" name="birth_date" value="{{ $user->birth_date }}" required>
                            @error('birth_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control select2">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control select2">
                                <option value="">Select Role</option>
                                <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="hiring_manager" {{ old('role', $user->role) == 'hiring_manager' ? 'selected' : '' }}>Hiring Manager</option>
                                <option value="recruiter" {{ old('role', $user->role) == 'recruiter' ? 'selected' : '' }}>Recruiter</option>
                                <option value="applicant" {{ old('role', $user->role) == 'applicant' ? 'selected' : '' }}>Applicant</option>
                            </select>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email_verified_at" class="kaem-subheading">Email Verified</label>
                            <input type="text" class="form-control datepicker datepicker-input" id="email_verified_at" name="email_verified_at" value="{{ $user->email_verified_at }}" required>
                            @error('email_verified_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('getUser') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
