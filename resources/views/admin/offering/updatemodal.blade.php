<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Modal Component -->
<div class="modal fade" id="editOfferingModal{{ $id }}" tabindex="-1" aria-labelledby="editOfferingModalLabel{{ $id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOfferingModalLabel{{ $id }}">Update Offering</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateOfferingForm" class="update-form-offering" action="{{ route('updateOffering', $id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label>Applicant</label>
                        <select name="id_user_job" class="form-control select2 inside-modal">
                            <option value="">Select Applicant</option>
                            @foreach ($userhrjobs as $applicant)
                                <option value="{{ $applicant->id }}" {{ $applicant->id == $offering->id_user_job ? 'selected' : '' }}>
                                    {{ $applicant->user->fullname }} | {{ $applicant->hrjob->job_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_user_job')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" id="id_job" name="id_job" value="{{ $offering->id_job }}">

                    <div class="form-group">
                        <label>Current File</label><br>
                        @if ($offering->offering_file)
                        <a href="{{ asset($offering->offering_file) }}" target="_blank">
                            View File
                        </a>
                        @else
                            <span>No File Available</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>New File</label>
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
