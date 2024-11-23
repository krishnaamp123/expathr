    <div class="card">
        <div class="card-header" style="color: white;">Education</div>
        <div class="card-body">
            <div class="">

                @if ($education->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($education as $educationn)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-heading">{{ $educationn->university ?? 'Unknown Education' }}</span><br>
                            <span class="kaem-subheading">{{ $educationn->degree }} - {{ $educationn->major }}</span><br>
                            <span class="kaem-text">{{ $educationn->start_date }} - {{ $educationn->end_date }}</span>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editEducationModal{{ $educationn->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyEducation', $educationn->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No education added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addEducationModal">
                Add Education
            </button>
        </div>
        </div>
    </div>

@foreach ($education as $educationn)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editEducationModal{{ $educationn->id }}" tabindex="-1" role="dialog" aria-labelledby="editEducationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEducationLabel">Edit Education</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editEducationForm" method="POST" action="{{ route('updateEducation', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="university" class="kaem-subheading">Institution</label>
                        <input type="text" class="form-control" id="university" name="university"  value="{{ $educationn->university }}" required>
                        @error('university')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="degree" class="kaem-subheading">Degree</label>
                        <input type="text" class="form-control" id="degree" name="degree"  value="{{ $educationn->degree }}" required>
                        @error('degree')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="major" class="kaem-subheading">Major</label>
                        <input type="text" class="form-control" id="major" name="major"  value="{{ $educationn->major }}" required>
                        @error('major')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="kaem-subheading">Start Date</label>
                        <input type="text" class="form-control datepicker datepicker-input" id="start_date" name="start_date" value="{{ $educationn->start_date }}" required>
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="kaem-subheading">End Date</label>
                        <input type="text" class="form-control datepicker datepicker-input" id="end_date" name="end_date" value="{{ $educationn->end_date }}" required>
                        @error('end_date')
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
<div class="modal fade" id="addEducationModal" tabindex="-1" role="dialog" aria-labelledby="addEducationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEducationLabel">Add Education</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.education.store')
            </div>
        </div>
    </div>
</div>
