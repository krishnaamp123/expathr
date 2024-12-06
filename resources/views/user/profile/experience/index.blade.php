    <div class="card">
        <div class="card-header" style="color: white;">Experience</div>
        <div class="card-body">
            <div class="">

                @if ($experience->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($experience as $experiencee)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-heading">{{ $experiencee->position ?? 'Unknown Experience' }}</span><br>
                            <span class="kaem-subheading">{{ $experiencee->company_name }} - {{ ucwords(str_replace('_', ' ', $experiencee->job_type)) }}</span><br>
                            <span class="kaem-text">{{ $experiencee->start_date }} - {{ $experiencee->end_date }}</span><br>
                            <span class="kaem-text">
                                @if (!empty($experiencee->location))
                                    {{ $experiencee->location }} -
                                @endif
                                {{ ucwords(str_replace('_', ' ', $experiencee->location_type)) }}
                            </span><br>
                            @if (!empty($experiencee->responsibility))
                            <span class="kaem-text">{{ $experiencee->responsibility }}</span><br>
                            @endif
                            @if (!empty($experiencee->job_report))
                            <span class="kaem-text">{{ $experiencee->job_report }}</span><br>
                            @endif
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editExperienceModal{{ $experiencee->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyExperience', $experiencee->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No experience added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addExperienceModal">
                Add Experience
            </button>
        </div>
        </div>
    </div>

@foreach ($experience as $experiencee)
<!-- Modal Edit Experience -->
<div class="modal fade" id="editExperienceModal{{ $experiencee->id }}" tabindex="-1" role="dialog" aria-labelledby="editExperienceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editExperienceLabel">Edit Experience</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editExperienceForm" method="POST" action="{{ route('updateExperience', $experiencee->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="position" class="kaem-subheading">Position</label>
                        <input type="text" class="form-control" id="position" name="position"  value="{{ $experiencee->position }}" required>
                        @error('position')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="job_type" class="kaem-subheading">Job Type</label>
                        <select name="job_type" class="form-control select2">
                            <option value="">Select Job Type</option>
                            <option value="full_time" {{ $experiencee->job_type == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ $experiencee->job_type == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="self_employed" {{ $experiencee->job_type == 'self_employed' ? 'selected' : '' }}>Self Employed</option>
                            <option value="freelancer" {{ $experiencee->job_type == 'freelancer' ? 'selected' : '' }}>Freelancer</option>
                            <option value="contract" {{ $experiencee->job_type == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ $experiencee->job_type == 'internship' ? 'selected' : '' }}>Internship</option>
                            <option value="seasonal" {{ $experiencee->job_type == 'seasonal' ? 'selected' : '' }}>Seasonal</option>
                        </select>
                        @error('job_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="company_name" class="kaem-subheading">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name"  value="{{ $experiencee->company_name }}" required>
                        @error('company_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location" class="kaem-subheading">Location</label>
                        <input type="text" class="form-control" id="location" name="location"  value="{{ $experiencee->location }}" required>
                        @error('location')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location_type" class="kaem-subheading">Location Type</label>
                        <select name="location_type" class="form-control select2">
                            <option value="">Select Location Type</option>
                            <option value="on_site" {{ $experiencee->location_type == 'on_site' ? 'selected' : '' }}>On Site</option>
                            <option value="hybrid" {{ $experiencee->location_type == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="remote" {{ $experiencee->location_type == 'remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                        @error('location_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="responsibility" class="kaem-subheading">Responsibility</label>
                        <textarea name="responsibility" class="form-control" id="responsibility" rows="5" required>{{ $experiencee->responsibility }}</textarea>
                        @error('responsibility')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="job_report" class="kaem-subheading">Job Report</label>
                        <textarea name="job_report" class="form-control" id="job_report" rows="5" required>{{ $experiencee->job_report }}</textarea>
                        @error('job_report')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="kaem-subheading">Start Date</label>
                        <input type="text" class="form-control datepicker datepicker-input" id="start_date" name="start_date" value="{{ $experiencee->start_date }}" required>
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="kaem-subheading">End Date</label>
                        <input type="text" class="form-control datepicker datepicker-input" id="end_date" name="end_date" value="{{ $experiencee->end_date }}" required>
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

<!-- Modal Add Experience -->
<div class="modal fade" id="addExperienceModal" tabindex="-1" role="dialog" aria-labelledby="addExperienceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExperienceLabel">Add Experience</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.experience.store')
            </div>
        </div>
    </div>
</div>
