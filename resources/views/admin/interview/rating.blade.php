@extends('admin.layout.app')
@section('title', 'Edit Interview')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Interview</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update Rating Interview</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateRating', $interview->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="redirectTo" value="{{ $redirectTo }}">

                        <!-- Rating -->
                        <div class="form-group">
                            <label>Rating</label>
                            <div class="rating-stars">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input
                                        type="radio"
                                        id="star-{{ $i }}"
                                        name="rating"
                                        value="{{ $i }}"
                                        @if($i == old('rating', $interview->rating)) checked @endif
                                        required>
                                    <label for="star-{{ $i }}" class="star">&#9733;</label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea name="comment" class="form-control" id="comment" rows="5">{{ $interview->comment }}</textarea>
                            @error('comment')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;">
                            <i class="fas fa-save"></i> Save
                        </button>
                        {{-- <a href="{{ route('getInterview') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a> --}}
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
@endsection
