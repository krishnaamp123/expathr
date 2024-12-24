<!-- Modal Component -->
<div class="modal fade" id="editUserHrjobModal{{ $id }}" tabindex="-1" aria-labelledby="editUserHrjobModalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserHrjobModalLabel{{ $id }}">Update Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('updateUserHrjob', $id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Job</label>
                        <select name="id_job" class="form-control select2">
                            <option value="">Select Job</option>
                            @foreach($hrjob as $job)
                                <option value="{{ $job->id }}" {{ $job->id == $userhrjob->id_job ? 'selected' : '' }}>
                                    {{ $job->job_name }}
                                </option>
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
                                <option value="{{ $applicant->id }}" {{ $applicant->id == $userhrjob->id_user ? 'selected' : '' }}>
                                    {{ $applicant->fullname }}
                                </option>
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
                            <option value="applicant" {{ old('status', $userhrjob->status) == 'applicant' ? 'selected' : '' }}>Applicant</option>
                            <option value="shortlist" {{ old('status', $userhrjob->status) == 'shortlist' ? 'selected' : '' }}>Shortlist</option>
                            <option value="phone_screen" {{ old('status', $userhrjob->status) == 'phone_screen' ? 'selected' : '' }}>Phone Screen</option>
                            <option value="hr_interview" {{ old('status', $userhrjob->status) == 'hr_interview' ? 'selected' : '' }}>HR Interview</option>
                            <option value="user_interview" {{ old('status', $userhrjob->status) == 'user_interview' ? 'selected' : '' }}>User Interview</option>
                            <option value="skill_test" {{ old('status', $userhrjob->status) == 'skill_test' ? 'selected' : '' }}>Skill Test</option>
                            <option value="reference_check" {{ old('status', $userhrjob->status) == 'reference_check' ? 'selected' : '' }}>Reference Check</option>
                            <option value="offering" {{ old('status', $userhrjob->status) == 'offering' ? 'selected' : '' }}>Offering</option>
                            <option value="rejected" {{ old('status', $userhrjob->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="hired" {{ old('status', $userhrjob->status) == 'hired' ? 'selected' : '' }}>Hired</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Salary Expectation</label>
                        <input type="number" name="salary_expectation" class="form-control" value="{{ old('salary_expectation', $userhrjob->salary_expectation) }}">
                        @error('salary_expectation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Availability</label>
                        <select name="availability" class="form-control select2">
                            <option value="">Select Availability</option>
                            <option value="immediately" {{ old('availability', $userhrjob->availability) == 'immediately' ? 'selected' : '' }}>Immediately</option>
                            <option value="<1_month_notice" {{ old('availability', $userhrjob->availability) == '<1_month_notice' ? 'selected' : '' }}>< 1 Month Notice</option>
                            <option value="1_month_notice" {{ old('availability', $userhrjob->availability) == '1_month_notice' ? 'selected' : '' }}>1 Month Notice</option>
                            <option value=">1_month_notice" {{ old('availability', $userhrjob->availability) == '>1_month_notice' ? 'selected' : '' }}>> 1 Month Notice</option>
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
