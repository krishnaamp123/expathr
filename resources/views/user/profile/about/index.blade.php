<div class="card">
    <div class="card-header" style="color: white;">About</div>
    <div class="card-body">
        <div class="">

            @if ($about->isNotEmpty())
                <ul class="list-group">
                    @foreach ($about as $aboutt)
                    <li class="list-group-item city-item position-relative">
                        <span class="kaem-subheading">{{ $aboutt->about ?? 'Unknown About' }}</span><br>
                        <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">
                            <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editAboutModal{{ $aboutt->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('destroyAbout', $aboutt->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="kaem-subheading">No about added yet.</p>
            @endif

        <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addAboutModal">
            Add About
        </button>
    </div>
    </div>
</div>

@foreach ($about as $aboutt)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editAboutModal{{ $aboutt->id }}" tabindex="-1" role="dialog" aria-labelledby="editAboutLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editAboutLabel">Edit About</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="editAboutForm" method="POST" action="{{ route('updateAbout', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="about" class="kaem-subheading">About</label>
                    <textarea name="about" class="form-control" id="about" rows="5" required>{{ $aboutt->about }}</textarea>
                    @error('about')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary kaem-subheading">Update</button>
            </form>
        </div>
    </div>
</div>
</div>
@endforeach

<!-- Modal Add Work Location -->
<div class="modal fade" id="addAboutModal" tabindex="-1" role="dialog" aria-labelledby="addAboutLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addAboutLabel">Add About</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @include('user.profile.about.store')
        </div>
    </div>
</div>
</div>
