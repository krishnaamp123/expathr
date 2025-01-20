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
                        <select id="applicantDropdown" name="id_user_job" class="form-control inside-modal">
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
                        <select id="referenceDropdown" name="id_reference" class="form-control inside-modal">
                            <option value="">Select Reference</option>
                            @foreach($references as $reference)
                                <option value="{{ $reference->id }}" data-user-id="{{ $reference->id_user }}">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const applicantDropdown = document.getElementById('applicantDropdown');
        const referenceDropdown = document.getElementById('referenceDropdown');

        applicantDropdown.addEventListener('change', function () {
            const selectedUserId = this.options[this.selectedIndex].getAttribute('data-user-id');
            console.log('Selected User ID:', selectedUserId); // Debugging

            // Filter references based on selected user ID
            Array.from(referenceDropdown.options).forEach(option => {
                const optionUserId = option.getAttribute('data-user-id');
                console.log('Reference Option User ID:', optionUserId); // Debugging

                if (optionUserId === selectedUserId || option.value === '') {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });

            // Reset reference dropdown value
            referenceDropdown.value = '';
        });
    });
</script>
