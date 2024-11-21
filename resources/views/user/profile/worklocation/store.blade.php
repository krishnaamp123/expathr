<form action="{{ route('storeWorkLocation') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="id_city" class="kaem-subheading">City</label>
        <select name="id_city" id="id_city" class="form-control select2 master-select2" required>
            <option value="">Select City</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
