@extends('admin.layout.app')
@section('title', 'Edit Question')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Question</h1>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update Question Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateQuestion', $question->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" name="question" class="form-control" value="{{ old('question', $question->question) }}">
                            @error('question')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('getQuestion') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
