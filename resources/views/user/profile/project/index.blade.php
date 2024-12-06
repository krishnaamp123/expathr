    <div class="card">
        <div class="card-header" style="color: white;">Project</div>
        <div class="card-body">
            <div class="">

                @if ($project->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($project as $projectt)
                        <li class="list-group-item city-item position-relative">
                            <div class="row">
                                @if ($projectt->media)
                                    <div class="col-md-3">
                                        <img src="{{ asset($projectt->media) }}" alt="Project Picture" class="img-fluid profile-project">
                                    </div>
                                    <div class="col-md-9">
                                @else
                                    <div class="col-md-12">
                                @endif
                                    <span class="kaem-heading">{{ $projectt->project_name ?? 'Unknown Project' }}</span><br>
                                    @if (!empty($projectt->description))
                                    <span class="kaem-text">{{ $projectt->description }}</span><br>
                                    @endif
                                    <span class="kaem-text">{{ $projectt->start_date }} - {{ $projectt->end_date }}</span>
                                </div>
                            </div>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editProjectModal{{ $projectt->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyProject', $projectt->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No project added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addProjectModal">
                Add Project
            </button>
        </div>
        </div>
    </div>

@foreach ($project as $projectt)
<!-- Modal Edit Project -->
<div class="modal fade" id="editProjectModal{{ $projectt->id }}" tabindex="-1" role="dialog" aria-labelledby="editProjectLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectLabel">Edit Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProjectForm" method="POST" action="{{ route('updateProject', $projectt->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="kaem-subheading">Current Image</label><br>
                        @if ($projectt->media)
                            <img src="{{ asset($projectt->media) }}" alt="Project Image" class="profile-picture-edit">
                        @else
                            <span class="kaem-text">No Image Available</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="kaem-subheading">New Image</label>
                        <input type="file" name="file" class="form-control">
                        @error('file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="project_name" class="kaem-subheading">Name</label>
                        <input type="text" class="form-control" id="project_name" name="project_name"  value="{{ $projectt->project_name }}" required>
                        @error('project_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="kaem-subheading">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="5" required>{{ $projectt->description }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="kaem-subheading">Start Date</label>
                        <input type="text" class="form-control datepicker datepicker-input" id="start_date" name="start_date" value="{{ $projectt->start_date }}" required>
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="kaem-subheading">End Date</label>
                        <input type="text" class="form-control datepicker datepicker-input" id="end_date" name="end_date" value="{{ $projectt->end_date }}" required>
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
<div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectLabel">Add Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.project.store')
            </div>
        </div>
    </div>
</div>
