<form action="{{ route('storeProject') }}" method="POST">
    @csrf

        <div class="form-group">
            <label for="project_name" class="kaem-subheading">Name</label>
            <input name="project_name" type="text" class="form-control form-control-user"
                id="exampleInputProjectName">
            @error('project_name')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="kaem-subheading">Description</label>
            <textarea name="description" class="form-control form-control-user" id="exampleInputDescription" rows="5"></textarea>
            @error('description')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="start_date" class="kaem-subheading">Start Date</label>
            <input type="text" class="form-control datepicker datepicker-input" id="start_date" name="start_date">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="end_date" class="kaem-subheading">End Date</label>
            <input type="text" class="form-control datepicker datepicker-input" id="end_date" name="end_date">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
