<form action="{{ route('storeEducation') }}" method="POST">
    @csrf
        <div class="form-group">
            <label for="university" class="kaem-subheading">Institution</label>
            <input name="university" type="text" class="form-control form-control-user"
                id="exampleInputUniversity">
            @error('university')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="degree" class="kaem-subheading">Degree</label>
            <input name="degree" type="text" class="form-control form-control-user"
                id="exampleInputDegree">
            @error('degree')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="major" class="kaem-subheading">Major</label>
            <input name="major" type="text" class="form-control form-control-user"
                id="exampleInputMajor">
            @error('major')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="start_date" class="kaem-subheading">Start Date</label>
            <input type="text" class="form-control datepicker datepicker-mm-yyyy" id="start_date" name="start_date">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="end_date" class="kaem-subheading">End Date</label>
            <input type="text" class="form-control datepicker datepicker-mm-yyyy" id="end_date" name="end_date">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
