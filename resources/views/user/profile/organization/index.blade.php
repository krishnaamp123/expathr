    <div class="card">
        <div class="card-header" style="color: white;">Organization</div>
        <div class="card-body">
            <div class="">

                @if ($organization->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($organization as $organizationn)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-heading">{{ $organizationn->organization ?? 'Unknown Organization' }}</span><br>
                            <span class="kaem-subheading">{{ $organizationn->position }} - {{ $organizationn->associated }}</span><br>
                            @if (!empty($organizationn->description))
                            <span class="kaem-text">{{ $organizationn->description }}</span><br>
                            @endif
                            <span class="kaem-text">{{ $organizationn->start_date }} - {{ $organizationn->end_date }}</span>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editOrganizationModal{{ $organizationn->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyOrganization', $organizationn->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No organization added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addOrganizationModal">
                Add Organization
            </button>
        </div>
        </div>
    </div>

@foreach ($organization as $organizationn)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editOrganizationModal{{ $organizationn->id }}" tabindex="-1" role="dialog" aria-labelledby="editOrganizationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrganizationLabel">Edit Organization</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editOrganizationForm" method="POST" action="{{ route('updateOrganization', $organizationn->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="organization" class="kaem-subheading">Organization</label>
                        <input type="text" class="form-control" id="organization" name="organization"  value="{{ $organizationn->organization }}" required>
                        @error('organization')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="position" class="kaem-subheading">Position</label>
                        <input type="text" class="form-control" id="position" name="position"  value="{{ $organizationn->position }}" required>
                        @error('position')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="associated" class="kaem-subheading">Associated</label>
                        <input type="text" class="form-control" id="associated" name="associated"  value="{{ $organizationn->associated }}" required>
                        @error('associated')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="kaem-subheading">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="5" required>{{ $organizationn->description }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="kaem-subheading">Start Date</label>
                        <input type="text" class="form-control datepicker datepicker-mm-yyyy" id="start_date" name="start_date" value="{{ $organizationn->start_date }}" required>
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="kaem-subheading">End Date</label>
                        <input type="text" class="form-control datepicker datepicker-mm-yyyy" id="end_date" name="end_date" value="{{ $organizationn->end_date }}" required>
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
<div class="modal fade" id="addOrganizationModal" tabindex="-1" role="dialog" aria-labelledby="addOrganizationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrganizationLabel">Add Organization</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.organization.store')
            </div>
        </div>
    </div>
</div>
