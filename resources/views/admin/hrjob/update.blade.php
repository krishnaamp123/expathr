@extends('admin.layout.app')
@section('title', 'Edit Job')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Job</h1>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update Job Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateHrjob', $hrjob->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Current Image</label><br>
                            @if ($hrjob->job_image)
                            <img src="{{ asset($hrjob->job_image) }}" alt="Job Image" class="profile-user">
                            @else
                                <span>No Image Available</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>New Image</label>
                            <input type="file" name="file" class="form-control">
                            @error('file')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Job Category</label>
                            <select name="id_category" class="form-control select2">
                                <option value="">Select Category</option>
                                @foreach($hrjobcategories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $hrjob->id_category ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Job Name</label>
                            <input type="text" name="job_name" class="form-control" value="{{ old('job_name', $hrjob->job_name) }}">
                            @error('job_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Job Type</label>
                            <select name="job_type" class="form-control select2">
                                <option value="">Select job_type</option>
                                <option value="full_time" {{ old('job_type', $hrjob->job_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ old('job_type', $hrjob->job_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="self_employed" {{ old('job_type', $hrjob->job_type) == 'self_employed' ? 'selected' : '' }}>Self Employed</option>
                                <option value="freelancer" {{ old('job_type', $hrjob->job_type) == 'freelancer' ? 'selected' : '' }}>Freelancer</option>
                                <option value="contract" {{ old('job_type', $hrjob->job_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="internship" {{ old('job_type', $hrjob->job_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                <option value="seasonal" {{ old('job_type', $hrjob->job_type) == 'seasonal' ? 'selected' : '' }}>Seasonal</option>
                            </select>
                            @error('job_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="job_report">Job Report</label>
                            <textarea name="job_report" class="form-control" id="job_report" rows="5" required>{{ $hrjob->job_report }}</textarea>
                            @error('job_report')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $hrjob->price) }}">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="5" required>{{ $hrjob->description }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="qualification">Qualification</label>
                            <textarea name="qualification" class="form-control" id="qualification" rows="5" required>{{ $hrjob->qualification }}</textarea>
                            @error('qualification')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location Type</label>
                            <select name="location_type" class="form-control select2">
                                <option value="">Select Location Type</option>
                                <option value="on_site" {{ old('location_type', $hrjob->location_type) == 'on_site' ? 'selected' : '' }}>On Site</option>
                                <option value="hybrid" {{ old('location_type', $hrjob->location_type) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                <option value="remote" {{ old('location_type', $hrjob->location_type) == 'remote' ? 'selected' : '' }}>Remote</option>
                            </select>
                            @error('location_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $hrjob->location) }}">
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Experience Min</label>
                            <input type="text" name="experience_min" class="form-control" value="{{ old('experience_min', $hrjob->experience_min) }}">
                            @error('experience_min')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Education Min</label>
                            <input type="text" name="education_min" class="form-control" value="{{ old('education_min', $hrjob->education_min) }}">
                            @error('education_min')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="expired">Expired</label>
                            <input type="text" class="form-control datepicker datepicker-input" id="expired" name="expired" value="{{ $hrjob->expired }}" required>
                            @error('expired')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Number Hired</label>
                            <input type="number" name="number_hired" class="form-control" value="{{ old('number_hired', $hrjob->number_hired) }}">
                            @error('number_hired')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Is Active</label>
                            <select name="is_active" class="form-control select2">
                                <option value="">Select Is Active</option>
                                <option value="yes" {{ old('is_active', $hrjob->is_active) == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('is_active', $hrjob->is_active) == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('is_active')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('getHrjob') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection