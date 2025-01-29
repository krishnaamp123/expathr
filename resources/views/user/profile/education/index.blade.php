    <div class="card">
        <div class="card-header" style="color: white;">Education</div>
        <div class="card-body">
            <div class="">

                @if ($education->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($education as $educationn)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-heading">{{ $educationn->university ?? 'Unknown Education' }}</span><br>
                            <span class="kaem-subheading">
                                {{ ucwords(str_replace('_', ' ', $educationn->degree)) }}
                                @if (!empty($educationn->major))
                                    - {{ $educationn->major }}
                                @endif
                            </span><br>
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
                <form id="editEducationForm" method="POST" action="{{ route('updateEducation', $educationn->id) }}">
                    @csrf
                    @method('PUT')
                    <label class="kaem-text">Edit the education you have taken!</label>
                    <div class="form-group">
                        <label for="university" class="kaem-subheading">Institution</label>
                        <input type="text" class="form-control kaem-sub" id="university" name="university"  value="{{ $educationn->university }}" required>
                        @error('university')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="degree" class="kaem-subheading">Degree</label>
                        <select name="degree" class="form-control select2">
                            <option value="">Select Degree</option>
                            <option value="elementary" {{ $educationn->degree == 'elementary' ? 'selected' : '' }}>Elementary School</option>
                            <option value="juniorhigh" {{ $educationn->degree == 'juniorhigh' ? 'selected' : '' }}>Junior High School</option>
                            <option value="seniorhigh" {{ $educationn->degree == 'seniorhigh' ? 'selected' : '' }}>Senior High School</option>
                            <option value="bachelor" {{ $educationn->degree == 'bachelor' ? 'selected' : '' }}>Bachelor's</option>
                            <option value="master" {{ $educationn->degree == 'master' ? 'selected' : '' }}>Master's</option>
                            <option value="doctoral" {{ $educationn->degree == 'doctoral' ? 'selected' : '' }}>Doctoral</option>
                        </select>
                        @error('degree')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="major" class="kaem-subheading">Major</label>
                        <input type="text" class="form-control kaem-sub" id="major" name="major"  value="{{ $educationn->major }}" required>
                        @error('major')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="kaem-subheading">Start Date</label>
                        <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy" id="start_date" name="start_date" value="{{ $educationn->start_date }}" required>
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="kaem-subheading">End Date</label>
                        <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy" id="end_date" name="end_date" value="{{ $educationn->end_date }}" required>
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
