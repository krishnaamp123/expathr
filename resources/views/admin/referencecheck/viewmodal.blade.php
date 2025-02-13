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
                    <p class="text-kaem mb-2" style="font-size: 16px;"><strong>{{ $reference->reference_name }}</strong></p>
                    <p class="mb-0" style="font-size: 15px;"><strong>Relation:</strong></p>
                    <p class="mb-1" style="font-size: 16px;">{{ $reference->relation }}</p>
                    <p class="mb-0" style="font-size: 15px;"><strong>Company Name:</strong></p>
                    <p class="mb-1" style="font-size: 16px;">{{ $reference->company_name }}</p>
                    <p class="mb-0" style="font-size: 15px;"><strong>Phone / Can Be Called:</strong></p>
                    <p class="mb-1" style="font-size: 16px;">{{ $reference->phone }} / {{ $reference->is_call }}</p>
                    @php
                        $comment = $row->referencechecks->where('id_reference', $reference->id)->first()?->comment;
                    @endphp
                    <p class="mb-0" style="font-size: 15px;"><strong>Comment:</strong></p>
                    @if ($comment)
                        <p class="mb-3" style="font-size: 16px;">
                            {{$comment}}
                        </p>
                    @else
                        <p class="mb-3" style="font-size: 16px;">Not Commented</p>
                    @endif
                @endforeach

            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
            </div>
        </div>
    </div>
</div>
