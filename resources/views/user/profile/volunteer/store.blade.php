<form action="{{ route('storeVolunteer') }}" method="POST">
    @csrf

    <label class="kaem-text">Add any volunteer activities you have done that are related to the job you are seeking!</label>
        <div class="form-group">
            <label for="organization" class="kaem-subheading">Organization</label>
            <input name="organization" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputOrganizationName">
            @error('organization')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="role" class="kaem-subheading">Role</label>
            <input name="role" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputRoleName">
            @error('role')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="issue" class="kaem-subheading">Issue</label>
            <input name="issue" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputIssueName">
            @error('issue')
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
            <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy" id="end_date" name="end_date">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
