@extends('admin.layout.app')
@section('title', 'Add Outlet')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Outlet</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Outlet Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeOutlet') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Outlet</label>
                            <input type="text" name="outlet_name" class="form-control">
                            @error('outlet_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
