<form action="{{ route('storeProject') }}" method="POST">
    @csrf

    <label class="kaem-text">Add projects that you have worked on that relate to the job you are looking for!</label>
        <div class="form-group">
            <label for="project_name" class="kaem-subheading">Name</label>
            <input name="project_name" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputProjectName">
            @error('project_name')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="kaem-subheading">Description</label>
            <textarea name="description" class="form-control kaem-sub form-control-user" id="exampleInputDescription" rows="5"></textarea>
            @error('description')
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
            <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy end-date-input"
                    id="end_date_pr"
                    name="end_date">
            <input type="checkbox"
                    class="present-checkbox"
                    data-target="end_date_pr"> Present
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
