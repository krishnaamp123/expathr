@extends('admin.layout.app')
@section('title', 'Add Job')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Job</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add a New Job Opening</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeHrjob') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label>Recruiter</label>
                            <select name="id_user" class="form-control select2" required>
                                <option value="">Select Recruiter</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                                @endforeach
                            </select>
                            @error('id_user')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Job Category</label>
                            <select name="id_category" class="form-control select2" required>
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
                            <label>Job Title</label>
                            <input type="text" name="job_name" class="form-control" required>
                            @error('job_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Job Type</label>
                            <select name="job_type" class="form-control select2" required>
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
                            <label for="job_report">Reporting To</label>
                            <textarea name="job_report" class="form-control" id="exampleInputJobReport" rows="1"></textarea>
                            @error('job_report')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Salary</label>
                            <input type="number" name="price" class="form-control">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="hide_salary" class="form-check-input" id="hideSalary" value="1">
                                <label class="form-check-label" for="hideSalary">Hide salary from applicant</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Responsibilities</label>
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
                            <select name="location_type" class="form-control select2" required>
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
                            <select name="id_city" class="form-control select2" required>
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
                            <label>Placement</label>
                            <select name="id_outlet" class="form-control select2" required>
                                <option value="">Select Placement</option>
                                @foreach($outlets as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->outlet_name }}</option>
                                @endforeach
                            </select>
                            @error('id_outlet')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Experience Min</label>
                            <input type="text" name="experience_min" class="form-control" required>
                            @error('experience_min')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Education Min</label>
                            <input type="text" name="education_min" class="form-control" required>
                            @error('education_min')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Expired</label>
                            <input type="text" name="expired" class="form-control datepicker datepicker-input" required>
                            @error('expired')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Number of Hire</label>
                            <input type="number" name="number_hired" class="form-control" required>
                            @error('number_hired')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Choose Assessment / Test</label>
                            <select name="id_form[]" class="form-control select2" multiple>
                                <option value="">Select Form</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}">{{ $form->form_name }}</option>
                                @endforeach
                            </select>
                            @error('id_form')
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
