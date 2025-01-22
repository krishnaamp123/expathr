<!-- Modal Component -->
<div class="modal fade" id="addOfferingModal" tabindex="-1" aria-labelledby="addOfferingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOfferingModalLabel">Add Offering</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('storeOffering') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Applicant</label>
                        <select name="id_user_job" class="form-control select2 inside-modal">
                            <option value="">Select Applicant</option>
                            @foreach($userhrjobs as $applicant)
                                <option value="{{ $applicant->id }}">
                                    {{ $applicant->user->fullname }} | {{ $applicant->hrjob->job_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_user_job')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" id="id_job" name="id_job" value="">

                    <div class="form-group">
                        <label>Offering File</label>
                        <input type="file" name="file" class="form-control">
                        @error('file')
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
