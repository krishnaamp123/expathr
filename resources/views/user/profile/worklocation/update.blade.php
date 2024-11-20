<form method="POST" action="{{ route('updateWorkLocation', $user->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label class="kaem-subheading">City</label>
        <select name="id_city" class="form-control select2" id="id_city" required>
            <option value="">Select City</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ $city->id == $user->id_city ? 'selected' : '' }}>
                    {{ $city->city_name }}
                </option>
            @endforeach
        </select>
        @error('id_city')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Update Work Location</button>
</form>
