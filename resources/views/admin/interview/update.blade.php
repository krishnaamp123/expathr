@extends('admin.layout.app')
@section('title', 'Edit Interview')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Interview</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update Interview</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateInterview', $interview->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Applicant</label>
                            <select name="id_user_job" class="form-control select2">
                                <option value="">Select Applicant</option>
                                @foreach($userhrjobs as $applicant)
                                    <option value="{{ $applicant->id }}" {{ $applicant->id == $interview->id_user_job ? 'selected' : '' }}>
                                        {{ $applicant->user->fullname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user_job')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Interviewer</label>
                            <select name="id_user" class="form-control select2">
                                <option value="">Select Interviewer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $interview->id_user ? 'selected' : '' }}>
                                        {{ $user->fullname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="interview_date">Interview Date</label>
                            <input type="text" class="form-control datepicker datepicker-input" id="interview_date" name="interview_date" value="{{ $interview->interview_date }}" required>
                            @error('interview_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Time</label>
                            <input type="text" name="time" class="form-control" value="{{ old('time', $interview->time) }}">
                            @error('time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $interview->location) }}">
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" name="link" class="form-control" value="{{ old('link', $interview->link) }}">
                            @error('link')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Rating</label>
                            <input type="number" name="rating" class="form-control" value="{{ old('rating', $interview->rating) }}">
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea name="comment" class="form-control" id="comment" rows="5">{{ $interview->comment }}</textarea>
                            @error('comment')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('getInterview') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection