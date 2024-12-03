@extends('admin.layout.app')
@section('title', 'Add Interview')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Interview</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Interview</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeInterview') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Applicant</label>
                            <select name="id_user_job" class="form-control select2">
                                <option value="">Select Applicant</option>
                                @foreach($userhrjobs as $applicant)
                                    <option value="{{ $applicant->id }}">{{ $applicant->user->fullname }}</option>
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
                                @foreach($users as $interviewer)
                                    <option value="{{ $interviewer->id }}">{{ $interviewer->fullname }}</option>
                                @endforeach
                            </select>
                            @error('id_user')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Interview Date</label>
                            <input type="text" name="interview_date" class="form-control datepicker datepicker-input">
                            @error('interview_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Time</label>
                            <input type="text" name="time" class="form-control">
                            @error('time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Rating</label>
                            <input type="number" name="rating" class="form-control">
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea name="comment" class="form-control" id="exampleInputComment" rows="5"></textarea>
                            @error('comment')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
