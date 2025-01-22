<!-- Modal Component -->
<div class="modal fade" id="addReferenceCheckModal" tabindex="-1" aria-labelledby="addReferenceCheckModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReferenceCheckModalLabel">Add Reference Check</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('storeReferenceCheck') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label>Applicant</label>
                        <select id="applicantDropdown" name="id_user_job" class="form-control select2 inside-modal">
                            <option value="">Select Applicant</option>
                            @foreach($userhrjobs as $applicant)
                                <option value="{{ $applicant->id }}" data-user-id="{{ $applicant->id_user }}">
                                    {{ $applicant->user->fullname }} | {{ $applicant->hrjob->job_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_user_job')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Reference Contact</label>
                        <select id="referenceDropdown" name="id_reference" class="form-control select2 inside-modal">
                            <option value="">Select Reference</option>
                            @foreach($references as $reference)
                                <option value="{{ $reference->id }}" data-user-id="{{ $reference->id_user}}">
                                    {{ $reference->reference_name }} | {{ $reference->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_reference')
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

    .select2-container--default .select2-selection--single {
        width: 100%;
    }

</style>

<script>
   $(document).ready(function () {
    $('#addReferenceCheckModal').on('shown.bs.modal', function () {
        $('.select2.inside-modal').select2({
            dropdownParent: $('#addReferenceCheckModal')
        });
    });

    // Event listener untuk Applicant dropdown
    $('#applicantDropdown').on('select2:select', function () {
        const selectedUserId = $(this).find(':selected').data('user-id');
        const referenceDropdown = $('#referenceDropdown');

        const allOptions = referenceDropdown.data('all-options') || referenceDropdown.html();
        if (!referenceDropdown.data('all-options')) {
            referenceDropdown.data('all-options', allOptions);
        }

        referenceDropdown.empty();

        $(allOptions).each(function () {
            const optionUserId = $(this).data('user-id');
            if (String(optionUserId) === String(selectedUserId) || !optionUserId) {
                referenceDropdown.append($(this));
            }
        });

        referenceDropdown.val(null).trigger('change.select2');
    });
});
</script>
