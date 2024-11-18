@extends('admin.layout.app')
@section('title', 'Edit User')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">User</h1>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update User Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateUser', $user->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Group ID</label>
                            <input type="number" name="id_group" class="form-control" value="{{ old('id_group', $user->id_group) }}">
                            @error('id_group')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}">
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="{{ old('password', $user->password) }}">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $user->customer_name) }}">
                            @error('customer_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>PIC Name</label>
                            <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name', $user->pic_name) }}">
                            @error('pic_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>PIC Phone</label>
                            <input type="number" name="pic_phone" class="form-control" value="{{ old('pic_phone', $user->pic_phone) }}">
                            @error('pic_phone')
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
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="retail" {{ old('role', $user->role) == 'retail' ? 'selected' : '' }}>Retail</option>
                                <option value="supermarket" {{ old('role', $user->role) == 'supermarket' ? 'selected' : '' }}>Supermarket</option>
                            </select>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
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
