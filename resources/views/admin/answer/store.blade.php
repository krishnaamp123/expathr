@extends('admin.layout.app')
@section('title', 'Add Answer')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Answer</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Answer</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeAnswer') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Job Name</label>
                            <select name="id_user_job" class="form-control select2">
                                <option value="">Select Job</option>
                                @foreach($userhrjobs as $userhrjob)
                                    <option value="{{ $userhrjob->id }}">{{ $userhrjob->hrjob->job_name }} - {{ $userhrjob->user->fullname }}</option>
                                @endforeach
                            </select>
                            @error('id_user_job')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        @foreach($forms as $form)
                            <div class="form-group">
                                <label>{{ $form->question->question }}</label>
                                <input type="hidden" name="answers[{{ $loop->index }}][id_form]" value="{{ $form->id }}">
                                <input type="text" class="form-control" name="answers[{{ $loop->index }}][answer]" required>
                                @error('answers.{{ $loop->index }}.answer')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <a href="{{ route('getUser') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
