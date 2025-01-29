<form action="{{ route('storeExperience') }}" method="POST">
    @csrf
    <label class="kaem-text">Add the experience you have had so far that is related to the job you are looking for!</label>
        <div class="form-group">
            <label for="position" class="kaem-subheading">Position</label>
            <input name="position" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputPosition">
            @error('position')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="job_type" class="kaem-subheading">Job Type</label>
            <select name="job_type" id="job_type" class="form-control select2">
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
            <label for="company_name" class="kaem-subheading">Company Name</label>
            <input name="company_name" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputCompanyName">
            @error('company_name')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="location" class="kaem-subheading">Location</label>
            <input name="location" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputLocation">
            @error('location')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="location_type" class="kaem-subheading">Location Type</label>
            <select name="location_type" id="location_type" class="form-control select2">
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
            <label for="responsibility" class="kaem-subheading">Responsibility</label>
            <textarea name="responsibility" class="form-control kaem-sub form-control-user" id="exampleInputResponsibility" rows="5"></textarea>
            @error('responsibility')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="job_report" class="kaem-subheading">Job Report</label>
            <textarea name="job_report" class="form-control kaem-sub form-control-user" id="exampleInputJobReport" rows="5"></textarea>
            @error('job_report')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="start_date" class="kaem-subheading">Start Date</label>
            <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy" id="start_date" name="start_date">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="end_date" class="kaem-subheading">End Date</label>
            <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy" id="end_date" name="end_date">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
