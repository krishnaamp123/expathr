<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Modal Component -->
<div class="modal fade" id="editRatingModal{{ $id }}" tabindex="-1" aria-labelledby="editRatingModalLabel{{ $id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRatingModalLabel{{ $id }}">Update Rating Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateRatingForm" class="update-form" action="{{ route('updateRating', $id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Rating -->
                    <div class="form-group">
                        <label>Rating</label>
                        <div class="rating-stars">
                            @for ($i = 5; $i >= 1; $i--)
                                <input
                                    type="radio"
                                    id="rating-star-{{ $i }}-{{ $id }}"
                                    name="rating"
                                    value="{{ $i }}"
                                    @if($i == old('rating', $rating)) checked @endif>
                                <label for="rating-star-{{ $i }}-{{ $id }}" class="star">&#9733;</label>
                            @endfor
                        </div>
                        @error('rating')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea name="comment" class="form-control" id="comment" rows="5">{{ old('comment', $comment) }}</textarea>
                        @error('comment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content">
                        <!-- Save Button -->
                        <button type="submit" name="button_action" value="save" class="btn btn-sm mr-2" style="background-color: #72A28A; color: white;">
                            <i class="fas fa-save"></i> Save
                        </button>

                        @if(auth()->user()->role !== 'interviewer')
                        <!-- Reject Button -->
                        <button type="submit" name="button_action" value="reject" class="btn btn-sm mr-2" style="background-color: #c03535; color: white;">
                            <i class="fas fa-times"></i> Reject
                        </button>

                        <!-- Next Button -->
                        <button type="submit" name="button_action" value="next" class="btn btn-sm" style="background-color: #969696; color: white;">
                            <i class="fas fa-arrow-right"></i> Next
                        </button>
                        @endif
                    </div>

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

</style>
