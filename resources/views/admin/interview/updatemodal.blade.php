<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Modal Component -->
<div class="modal fade" id="editInterviewModal{{ $id }}" tabindex="-1" aria-labelledby="editInterviewModalLabel{{ $id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInterviewModalLabel{{ $id }}">Update Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateInterviewForm" class="update-form" action="{{ route('updateInterview', $id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Applicant</label>
                        <select name="id_user_job" class="form-control select2 inside-modal">
                            <option value="">Select Applicant</option>
                            @foreach ($userhrjobs as $applicant)
                                <option value="{{ $applicant->id }}" {{ $applicant->id == $interview->id_user_job ? 'selected' : '' }}>
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
                        <select name="interviewers[]" class="form-control select2 inside-modal" multiple>
                            @foreach ($users as $interviewer)
                                <option value="{{ $interviewer->id }}"
                                    {{ in_array($interviewer->id, $interview->interviewers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $interviewer->fullname }}
                                </option>
                            @endforeach
                        </select>
                        @error('interviewers')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="interview_date">Interview Date</label>
                        <input type="text" class="form-control datepicker datepicker-input" id="interview_date" name="interview_date" value="{{ $interview->interview_date }}">
                        @error('interview_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Interview Time</label>
                        <input type="time" name="time" class="form-control" value="{{ old('time', $interview->time) }}">
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
                        <label>Confirm Attendance</label>
                        <select name="arrival" class="form-control select2 inside-modal">
                            <option value="">Select Confirm Attendance</option>
                            <option value="yes" {{ old('arrival', $interview->arrival) == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ old('arrival', $interview->arrival) == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('arrival')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Interview Process</label>
                        <select name="interviewed" class="form-control select2 inside-modal">
                            <option value="">Select Interview Process</option>
                            <option value="yes" {{ old('interviewed', $interview->interviewed) == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ old('interviewed', $interview->interviewed) == 'no' ? 'selected' : '' }}>No</option>
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
                                    @if($i == old('rating', $interview->rating)) checked @endif>
                                <label for="update-star-{{ $i }}-{{ $id }}" class="star">&#9733;</label>
                            @endfor
                        </div>
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
