<!-- Modal Component -->
<div class="modal fade" id="addSkillTestModal" tabindex="-1" aria-labelledby="addSkillTestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkillTestModalLabel">Add Skill Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('storeSkillTest') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label>Applicant</label>
                        <select name="id_user_job" class="form-control select2 inside-modal">
                            <option value="">Select Applicant</option>
                            @foreach($userhrjobs as $applicant)
                                <option value="{{ $applicant->id }}">{{ $applicant->user->fullname }} | {{ $applicant->hrjob->job_name }}</option>
                            @endforeach
                        </select>
                        @error('id_user_job')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Score -->
                    <div class="form-group mt-3">
                        <label for="score">Score (1-10)</label>
                        <input
                            type="range"
                            id="score"
                            name="score"
                            class="form-range custom-range"
                            min="1"
                            max="10"
                            value="5"
                            oninput="document.getElementById('scoreValue').textContent = this.value;">
                        <div class="d-flex justify-content-between">
                            <small>1</small>
                            <strong id="scoreValue">5</strong>
                            <small>10</small>
                        </div>
                        @error('score')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Rating -->
                    <div class="form-group">
                        <label>Rating</label>
                        <div class="rating-stars">
                            @for ($i = 5; $i >= 1; $i--)
                                <input
                                    type="radio"
                                    id="star-{{ $i }}"
                                    name="rating">
                                <label for="star-{{ $i }}" class="star">&#9733;</label>
                            @endfor
                        </div>
                        @error('rating')
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

    .form-range.custom-range {
        -webkit-appearance: none;
        width: 100%;
        height: 8px;
        background: #d3d3d3; /* Bar (abu-abu) */
        border-radius: 5px;
        outline: none;
        transition: background 0.3s;
    }

    .form-range.custom-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #72A28A; /* Thumb (hijau) */
        cursor: pointer;
        border: 2px solid #d3d3d3; /* Border abu-abu */
        transition: background 0.3s, border-color 0.3s;
    }

    .form-range.custom-range::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #72A28A; /* Thumb (hijau) */
        cursor: pointer;
        border: 2px solid #d3d3d3; /* Border abu-abu */
    }

    .form-range.custom-range:focus::-webkit-slider-thumb {
        border-color: #72A28A; /* Border hijau saat fokus */
    }

    .form-range.custom-range:focus {
        outline: none;
    }
</style>
