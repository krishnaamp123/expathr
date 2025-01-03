<form action="{{ route('storeEmergency') }}" method="POST">
    @csrf
        <label class="kaem-text">Emergency contact number that can be contacted!</label>
        <div class="form-group">
            <label for="emergency_name" class="kaem-subheading">Name</label>
            <input name="emergency_name" type="text" class="form-control form-control-user"
                id="exampleInputEmergencyName">
            @error('emergency_name')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="emergency_relation" class="kaem-subheading">Relation</label>
            <input name="emergency_relation" type="text" class="form-control form-control-user"
                id="exampleInputEmergencyRelation">
            @error('emergency_relation')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="emergency_phone" class="kaem-subheading">Phone</label>
            <input name="emergency_phone" type="number" class="form-control form-control-user"
                id="exampleInputEmergencyPhone">
            @error('emergency_phone')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
