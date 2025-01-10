<!-- userInterviewModal.blade.php -->
<div class="modal fade" id="userInterviewModal" tabindex="-1" role="dialog" aria-labelledby="userInterviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userInterviewModalLabel">Add User Interview Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <form action="{{ route('storeUserInterview') }}" method="POST">
                        @csrf
                    <div class="form-group">
                        <label>Applicant</label>
                        <input
                            type="text"
                            name="fullname"
                            class="form-control"
                            id="ModalUserJobName"
                            value=""
                            readonly
                        >
                        <input
                            type="hidden"
                            name="id_user_job"
                            id="ModalUserJobId"
                            value=""
                        >
                    </div>
                    <div class="form-group">
                        <label>Interviewers</label>
                        <select name="user_interviewers[]" class="form-control select2 inside-modal" multiple>
                            <option value="">Select Interviewers</option>
                            @foreach($users as $userinterviewer)
                                <option value="{{ $userinterviewer->id }}">{{ $userinterviewer->fullname }}</option>
                            @endforeach
                        </select>
                        @error('user_interviewers')
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
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
                </form>
                </div>
        </div>
    </div>
</div>
