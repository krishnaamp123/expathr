<form action="{{ route('storeReference') }}" method="POST">
    @csrf
        <label class="kaem-text">Contact references must added at least 2 contacts!</label>
        <div class="form-group">
            <label for="reference_name" class="kaem-subheading">Reference Name</label>
            <input name="reference_name" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputReferenceName">
            @error('reference_name')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="relation" class="kaem-subheading">Relation</label>
            <input name="relation" type="text" class="form-control kaem-sub form-control-user"
            id="exampleInputRelation">
            @error('relation')
            <div class="text-danger">{{$message}}</div>
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
            <label for="phone" class="kaem-subheading">Phone</label>
            <input name="phone" type="number" class="form-control kaem-sub form-control-user"
                id="exampleInputPhone">
            @error('phone')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="is_call" class="kaem-subheading">Can Be Contacted?</label>
            <select name="is_call" id="is_call" class="form-control select2">
                <option value="">Select</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            @error('is_call')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
