@extends('admin.layout.app')
@section('title', 'Edit Job Category')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Job Category</h1>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update Job Category Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateHrjobCategory', $hrjobcategory->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="category_name" class="form-control" value="{{ old('category_name', $hrjobcategory->category_name) }}">
                            @error('category_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('getHrjobCategory') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
