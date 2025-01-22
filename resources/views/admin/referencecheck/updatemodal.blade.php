<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Modal Component -->
<div class="modal fade" id="editReferenceCheckModal{{ $id }}" tabindex="-1" aria-labelledby="editReferenceCheckModalLabel{{ $id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReferenceCheckModalLabel{{ $id }}">Update Reference Check</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateReferenceCheckForm" class="update-form" action="{{ route('updateReferenceCheck', $id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Applicant</label>
                        <select id="applicantDropdown{{ $id }}" name="id_user_job" class="form-control select2 inside-modal">
                            <option value="">Select Applicant</option>
                            @foreach ($userhrjobs as $applicant)
                                <option value="{{ $applicant->id }}" data-user-id="{{ $applicant->id_user }}" {{ $applicant->id == $referencecheck->id_user_job ? 'selected' : '' }}>
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
                        <select id="referenceDropdown{{ $id }}" name="id_reference" class="form-control select2 inside-modal">
                            <option value="">Select Reference</option>
                            @foreach($references as $reference)
                                <option value="{{ $reference->id }}" data-user-id="{{ $reference->id_user }}" {{ $reference->id == $referencecheck->id_reference ? 'selected' : '' }}>
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
                        <textarea name="comment" class="form-control" id="comment" rows="5">{{ old('comment', $comment) }}</textarea>
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

    .select2-container--default .select2-selection--single {
        width: 100%;
    }

</style>

<script>
   $(document).ready(function () {
    // Inisialisasi Select2 ketika modal ditampilkan
    $('div[id^="editReferenceCheckModal"]').on('shown.bs.modal', function (e) {
        const modal = $(this);

        // Inisialisasi Select2 di dalam modal ini
        modal.find('.select2.inside-modal').select2({
            dropdownParent: modal
        });

        // Event listener untuk dropdown Applicant
        modal.find('[id^="applicantDropdown"]').on('select2:select', function () {
            const selectedUserId = $(this).find(':selected').data('user-id');
            const referenceDropdown = modal.find('[id^="referenceDropdown"]');

            const allOptions = referenceDropdown.data('all-options') || referenceDropdown.html();
            if (!referenceDropdown.data('all-options')) {
                referenceDropdown.data('all-options', allOptions);
            }

            // Reset opsi Reference
            referenceDropdown.empty();

            // Tambahkan opsi yang relevan
            $(allOptions).each(function () {
                const optionUserId = $(this).data('user-id');
                if (String(optionUserId) === String(selectedUserId) || !optionUserId) {
                    referenceDropdown.append($(this));
                }
            });

            // Reset nilai dropdown dan perbarui Select2
            referenceDropdown.val(null).trigger('change.select2');
        });
    });
});

</script>
