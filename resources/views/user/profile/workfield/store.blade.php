<form action="{{ route('storeWorkField') }}" method="POST">
    @csrf
    <label class="kaem-text">Select all the fields you want to place!</label>
    <div class="form-group">
        <label for="id_field" class="kaem-subheading">Field</label>
        <select name="id_field" id="id_field" class="form-control select2" required>
            <option value="">Select Field</option>
            @foreach ($fields as $field)
                <option value="{{ $field->id }}">{{ $field->field_name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
