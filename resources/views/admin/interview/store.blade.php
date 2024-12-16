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
                        <input type="hidden" name="redirectTo" value="{{ $redirectTo }}">

                        <div class="form-group">
                            <label>Applicant</label>
                            <select name="id_user_job" class="form-control select2">
                                <option value="">Select Applicant</option>
                                @foreach($userhrjobs as $applicant)
                                    <option value="{{ $applicant->id }}">{{ $applicant->user->fullname }} | {{ $applicant->hrjob->job_name }}</option>
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
                            <input type="time" name="time" class="form-control">
                            @error('time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control">
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" name="link" class="form-control">
                            @error('link')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rating -->
                        <div class="form-group">
                            <label>Rating</label>
                            <div class="rating-stars">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input
                                        type="radio"
                                        id="star-{{ $i }}"
                                        name="rating">
                                    <label for="star-{{ $i }}" class="star">&#9733;</label>
                                @endfor
                            </div>
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
    <!-- Custom CSS -->
    <style>
        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .star {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            margin: 0 2px;
        }

        .star:hover,
        .star:hover ~ .star,
        input[type="radio"]:checked ~ .star {
            color: #ffc107;
        }

        input[type="radio"] {
            display: none;
        }
    </style>
@endsection
