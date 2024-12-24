@extends('admin.layout.app')
@section('title', 'Edit Placement')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Placement</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update Placement Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateOutlet', $outlet->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Placement</label>
                            <input type="text" name="outlet_name" class="form-control" value="{{ old('outlet_name', $outlet->outlet_name) }}">
                            @error('outlet_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('getOutlet') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
