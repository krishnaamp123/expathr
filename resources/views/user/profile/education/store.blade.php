<form action="{{ route('storeEducation') }}" method="POST">
    @csrf
    <label class="kaem-text">Add the education you have taken!</label>
        <div class="form-group">
            <label for="university" class="kaem-subheading">Institution</label>
            <input name="university" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputUniversity">
            @error('university')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="degree" class="kaem-subheading">Degree</label>
            <select name="degree" id="degree" class="form-control select2">
                <option value="">Select Degree</option>
                <option value="elementary">Elementary School</option>
                <option value="juniorhigh">Junior High School</option>
                <option value="seniorhigh">Senior High School</option>
                <option value="bachelor">Bachelor's</option>
                <option value="master">Master's</option>
                <option value="doctoral">Doctoral</option>
            </select>
            @error('degree')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="major" class="kaem-subheading">Major</label>
            <input name="major" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputMajor">
            @error('major')
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

        {{-- <div class="form-group">
            <label for="end_date" class="kaem-subheading">End Date</label>
            <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy" id="end_date" name="end_date">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div> --}}

        <div class="form-group">
            <label for="end_date" class="kaem-subheading">End Date</label>
            <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy end-date-input"
                    id="end_date_ed"
                    name="end_date">
            <input type="checkbox"
                    class="present-checkbox"
                    data-target="end_date_ed"> Present
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
