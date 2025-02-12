<div class="modal fade" id="viewReferenceModal{{ $row->id }}" tabindex="-1" aria-labelledby="viewReferenceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReferenceModalLabel">User References</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach ($row->user->reference as $reference)
                    <p class="text-kaem mb-0">{{ $reference->reference_name }}</p>
                    <p class="mb-0">{{ $reference->relation }}</p>
                    <p class="text-kaem mb-0">{{ $reference->company_name }}</p>
                    <p class="text-kaem mb-0">{{ $reference->phone }}</p>
                    <p class="text-kaem mb-0">{{ $reference->is_call }}</p>
                @endforeach
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
            </div>
        </div>
    </div>
</div>
