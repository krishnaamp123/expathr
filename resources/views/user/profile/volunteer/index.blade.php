    <div class="card">
        <div class="card-header" style="color: white;">Volunteer</div>
        <div class="card-body">
            <div class="">

                @if ($volunteer->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($volunteer as $volunteerr)
                        <li class="list-group-item city-item position-relative">
                                    <span class="kaem-heading">{{ $volunteerr->organization ?? 'Unknown Volunteer' }}</span><br>
                                    <span class="kaem-subheading">{{ $volunteerr->role }} - {{ $volunteerr->issue }}</span><br>
                                    @if (!empty($volunteerr->description))
                                    <span class="kaem-text">{{ $volunteerr->description }}</span><br>
                                    @endif
                                    <span class="kaem-text">{{ $volunteerr->start_date }} - {{ $volunteerr->end_date }}</span>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editVolunteerModal{{ $volunteerr->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyVolunteer', $volunteerr->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No volunteer added yet.</p>
                @endif

                <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addVolunteerModal">
                    Add Volunteer
                </button>
            </div>
        </div>
    </div>

@foreach ($volunteer as $volunteerr)
<!-- Modal Edit Volunteer -->
<div class="modal fade" id="editVolunteerModal{{ $volunteerr->id }}" tabindex="-1" role="dialog" aria-labelledby="editVolunteerLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVolunteerLabel">Edit Volunteer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editVolunteerForm" method="POST" action="{{ route('updateVolunteer', $volunteerr->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="organization" class="kaem-subheading">Organization</label>
                        <input type="text" class="form-control" id="organization" name="organization"  value="{{ $volunteerr->organization }}" required>
                        @error('organization')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role" class="kaem-subheading">Role</label>
                        <input type="text" class="form-control" id="role" name="role"  value="{{ $volunteerr->role }}" required>
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="issue" class="kaem-subheading">Issue</label>
                        <input type="text" class="form-control" id="issue" name="issue"  value="{{ $volunteerr->issue }}" required>
                        @error('issue')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="kaem-subheading">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="5" required>{{ $volunteerr->description }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="kaem-subheading">Start Date</label>
                        <input type="text" class="form-control datepicker datepicker-mm-yyyy" id="start_date" name="start_date" value="{{ $volunteerr->start_date }}" required>
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="kaem-subheading">End Date</label>
                        <input type="text" class="form-control datepicker datepicker-mm-yyyy" id="end_date" name="end_date" value="{{ $volunteerr->end_date }}" required>
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
<div class="modal fade" id="addVolunteerModal" tabindex="-1" role="dialog" aria-labelledby="addVolunteerLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVolunteerLabel">Add Volunteer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.volunteer.store')
            </div>
        </div>
    </div>
</div>
