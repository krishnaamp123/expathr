<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Modal Component -->
<div class="modal fade" id="editUserInterviewModal{{ $id }}" tabindex="-1" aria-labelledby="editUserInterviewModalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserInterviewModalLabel{{ $id }}">Update User Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateUserInterviewForm" class="update-form" action="{{ route('updateUserInterview', $id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Applicant</label>
                        <select name="id_user_job" class="form-control select2 inside-modal">
                            <option value="">Select Applicant</option>
                            @foreach ($userhrjobs as $applicant)
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
                        <label>Interviewers</label>
                        <select name="user_interviewers[]" class="form-control select2" multiple>
                            @foreach ($users as $userinterviewer)
                                <option value="{{ $userinterviewer->id }}"
                                    {{ in_array($userinterviewer->id, $userinterview->user_interviewers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $userinterviewer->fullname }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_interviewers')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="interview_date">Interview Date</label>
                        <input type="text" class="form-control datepicker datepicker-input" id="interview_date" name="interview_date" value="{{ $userinterview->interview_date }}">
                        @error('interview_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Interview Time</label>
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
                        <label>Confirm Attendance</label>
                        <select name="arrival" class="form-control select2 inside-modal">
                            <option value="">Select Confirm Attendance</option>
                            <option value="yes" {{ old('arrival', $userinterview->arrival) == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ old('arrival', $userinterview->arrival) == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('arrival')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Interview Process</label>
                        <select name="interviewed" class="form-control select2 inside-modal">
                            <option value="">Select Interview Process</option>
                            <option value="yes" {{ old('interviewed', $userinterview->interviewed) == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ old('interviewed', $userinterview->interviewed) == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('interviewed')
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
                                    id="update-star-{{ $i }}-{{ $id }}"
                                    name="rating"
                                    value="{{ $i }}"
                                    @if($i == old('rating', $userinterview->rating)) checked @endif>
                                <label for="update-star-{{ $i }}-{{ $id }}" class="star">&#9733;</label>
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

                    <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;">
                        <i class="fas fa-save"></i> Save
                    </button>
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

    .select2-container--default .select2-selection--single {
        width: 100%;
    }
</style>
