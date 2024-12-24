<!-- Modal Component -->
<div class="modal fade" id="addUserHrjobModal" tabindex="-1" aria-labelledby="addUserHrjobModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserHrjobModalLabel">Add User Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('storeUserHrjob') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label>Job</label>
                        <select name="id_job" class="form-control select2">
                            <option value="">Select Job</option>
                            @foreach($hrjob as $job)
                                <option value="{{ $job->id }}">{{ $job->job_name }}</option>
                            @endforeach
                        </select>
                        @error('id_job')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Applicant</label>
                        <select name="id_user" class="form-control select2">
                            <option value="">Select Applicant</option>
                            @foreach($user as $applicant)
                                <option value="{{ $applicant->id }}">{{ $applicant->fullname }}</option>
                            @endforeach
                        </select>
                        @error('id_user')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control select2">
                            <option value="">Select Status</option>
                            <option value="applicant">Applicant</option>
                            <option value="shortlist">Shortlist</option>
                            <option value="phone_screen">Phone Screen</option>
                            <option value="hr_interview">HR Interview</option>
                            <option value="user_interview">User Interview</option>
                            <option value="skill_test">Skill Test</option>
                            <option value="reference_check">Reference Check</option>
                            <option value="offering">Offering</option>
                            <option value="rejected">Rejected</option>
                            <option value="hired">Hired</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Salary Expectation</label>
                        <input type="number" name="salary_expectation" class="form-control">
                        @error('salary_expectation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Availability</label>
                        <select name="availability" class="form-control select2">
                            <option value="">Select Availability</option>
                            <option value="immediately">Immediately</option>
                            <option value="<1_month_notice">< 1 Month Notice </option>
                            <option value="1_month_notice">1 Month Notice </option>
                            <option value=">1_month_notice">> 1 Month Notice </option>
                        </select>
                        @error('availability')
                            <div class="text-danger">{{ $message }}</div>
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
