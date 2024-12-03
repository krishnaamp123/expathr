@extends('admin.layout.app')
@section('title', 'Add Job')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Job</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Job Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeHrjob') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Image</label>
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
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('id_category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Job Name</label>
                            <input type="text" name="job_name" class="form-control">
                            @error('job_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Job Type</label>
                            <select name="job_type" class="form-control select2">
                                <option value="">Select Job Type</option>
                                <option value="full_time">Full Time</option>
                                <option value="part_time">Part Time</option>
                                <option value="self_employed">Self Employed</option>
                                <option value="freelancer">Freelancer</option>
                                <option value="contract">Contract</option>
                                <option value="internship">Internship</option>
                                <option value="seasonal">Seasonal</option>
                            </select>
                            @error('job_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="job_report">Job Report</label>
                            <textarea name="job_report" class="form-control" id="exampleInputJobReport" rows="5"></textarea>
                            @error('job_report')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" id="exampleInputDescription" rows="5"></textarea>
                            @error('description')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="qualification">Qualification</label>
                            <textarea name="qualification" class="form-control" id="exampleInputQualification" rows="5"></textarea>
                            @error('qualification')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location Type</label>
                            <select name="location_type" class="form-control select2">
                                <option value="">Select Location Type</option>
                                <option value="on_site">On Site</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="remote">Remote</option>
                            </select>
                            @error('location_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location</label>
                            <select name="id_city" class="form-control select2">
                                <option value="">Select Location</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            @error('id_city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Experience Min</label>
                            <input type="text" name="experience_min" class="form-control">
                            @error('experience_min')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Education Min</label>
                            <input type="text" name="education_min" class="form-control">
                            @error('education_min')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Expired</label>
                            <input type="text" name="expired" class="form-control datepicker datepicker-input">
                            @error('expired')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Number Hired</label>
                            <input type="number" name="number_hired" class="form-control">
                            @error('number_hired')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Is Active</label>
                            <select name="is_active" class="form-control select2">
                                <option value="">Select Is Active</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            @error('is_active')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
