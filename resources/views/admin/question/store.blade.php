@extends('admin.layout.app')
@section('title', 'Add Question')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Question</h1>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Question Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeQuestion') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" name="question" class="form-control">
                            @error('question')
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
