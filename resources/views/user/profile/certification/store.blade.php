<form action="{{ route('storeCertification') }}" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="form-group">
            <label class="kaem-subheading">New Image</label>
            <input type="file" name="file" class="form-control">
            @error('file')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="lisence_name" class="kaem-subheading">Lisence Name</label>
            <input name="lisence_name" type="text" class="form-control form-control-user"
                id="exampleInputLisenceName">
            @error('lisence_name')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="organization" class="kaem-subheading">Organization</label>
            <input name="organization" type="text" class="form-control form-control-user"
                id="exampleInputOrganizationName">
            @error('organization')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="id_credentials" class="kaem-subheading">Id Credentials</label>
            <input name="id_credentials" type="text" class="form-control form-control-user"
                id="exampleInputIdCredentialsName">
            @error('id_credentials')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="url_credentials" class="kaem-subheading">Url Credentials</label>
            <input name="url_credentials" type="text" class="form-control form-control-user"
                id="exampleInputUrlCredentialsName">
            @error('url_credentials')
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
