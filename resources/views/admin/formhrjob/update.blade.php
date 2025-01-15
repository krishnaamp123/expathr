@extends('admin.layout.app')
@section('title', 'Edit Form Job')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Form Job</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update Job</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateFormHrjob', $formhrjob->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Job Name</label>
                            <select name="id_job" class="form-control select2">
                                <option value="">Select Job</option>
                                @foreach($hrjobs as $job)
                                    <option value="{{ $job->id }}" {{ $job->id == $formhrjob->id_job ? 'selected' : '' }}>
                                        {{ $job->job_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_job')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Form</label>
                            <select name="id_form" class="form-control select2">
                                <option value="">Select Form</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}" {{ $form->id == $formhrjob->id_form ? 'selected' : '' }}>
                                        {{ $form->form_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_form')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('getFormHrjob') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
