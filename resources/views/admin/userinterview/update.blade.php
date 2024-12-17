@extends('admin.layout.app')
@section('title', 'Edit User Interview')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">User Interview</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update User Interview</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateUserInterview', $userinterview->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="redirectTo" value="{{ $redirectTo }}">

                        <div class="form-group">
                            <label>Applicant</label>
                            <select name="id_user_job" class="form-control select2">
                                <option value="">Select Applicant</option>
                                @foreach($userhrjobs as $applicant)
                                    <option value="{{ $applicant->id }}" {{ $applicant->id == $userinterview->id_user_job ? 'selected' : '' }}>
                                        {{ $applicant->user->fullname }} | {{ $applicant->hrjob->job_name }}
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
                                    <option value="{{ $user->id }}" {{ $user->id == $userinterview->id_user ? 'selected' : '' }}>
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
                            <input type="text" class="form-control datepicker datepicker-input" id="interview_date" name="interview_date" value="{{ $userinterview->interview_date }}" required>
                            @error('interview_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Time</label>
                            <input type="time" name="time" class="form-control" value="{{ old('time', $userinterview->time) }}">
                            @error('time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $userinterview->location) }}">
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" name="link" class="form-control" value="{{ old('link', $userinterview->link) }}">
                            @error('link')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Arrival</label>
                            <select name="arrival" class="form-control select2">
                                <option value="">Select Arrival</option>
                                <option value="yes" {{ old('arrival', $userinterview->arrival) == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('arrival', $userinterview->arrival) == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('arrival')
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
                                        name="rating"
                                        value="{{ $i }}"
                                        @if($i == old('rating', $userinterview->rating)) checked @endif>
                                    <label for="star-{{ $i }}" class="star">&#9733;</label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea name="comment" class="form-control" id="comment" rows="5">{{ $userinterview->comment }}</textarea>
                            @error('comment')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        {{-- <a href="{{ route('getUserInterview') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a> --}}
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
