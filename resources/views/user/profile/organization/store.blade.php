<form action="{{ route('storeOrganization') }}" method="POST">
    @csrf
    <label class="kaem-text">Add organizations you've been involved with that are related to the job you're looking for!</label>
        <div class="form-group">
            <label for="organization" class="kaem-subheading">Organization</label>
            <input name="organization" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputOrganization">
            @error('organization')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="position" class="kaem-subheading">Position</label>
            <input name="position" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputPosition">
            @error('position')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="associated" class="kaem-subheading">Associated</label>
            <input name="associated" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputAssociated">
            @error('associated')
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
                    id="end_date_or"
                    name="end_date">
            <input type="checkbox"
                    class="present-checkbox"
                    data-target="end_date_or"> Present
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
