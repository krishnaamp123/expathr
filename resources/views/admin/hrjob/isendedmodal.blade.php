<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Modal Component -->
<div class="modal fade" id="editIsEndedModal{{ $id }}" tabindex="-1" aria-labelledby="editIsEndedModalLabel{{ $id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editIsEndedModalLabel{{ $id }}">Update Job Ended</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateIsEndedForm" class="update-form" action="{{ route('updateIsEnded', $id) }}" method="POST">
                    @csrf

                    <input type="hidden" name="is_ended" value="yes">

                    <div class="form-group">
                        <label for="hiring_cost">Hiring Cost</label>
                        <input type="number" id="hiring_cost" name="hiring_cost" class="form-control"required>
                        @error('hiring_cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Applicant Hired</label>
                        <select name="selected_offerings[]" class="form-control select2 inside-modal" multiple>
                            @foreach ($offerings as $offering)
                                @if ($offering->userHrjob && $offering->userHrjob->id_job == $hrjobs->id)
                                    <option value="{{ $offering->id }}">
                                        {{ $offering->userHrjob->user->fullname ?? 'Unknown User' }} |
                                        {{ $offering->userHrjob->hrjob->job_name ?? 'Unknown Job' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('selected_offerings')
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
