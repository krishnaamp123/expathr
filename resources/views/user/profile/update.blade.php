<form method="POST" action="{{ route('updateProfile', $user->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Current Image</label><br>
        @if ($user->profile_pict)
            <img src="{{ asset($user->profile_pict) }}" alt="User Image" width="100" height="100">
        @else
            <span>No Image Available</span>
        @endif
    </div>

    <div class="form-group">
        <label>New Image</label>
        <input type="file" name="file" class="form-control">
        @error('file')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>City</label>
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

    <div class="form-group">
        <label for="fullname">Fullname</label>
        <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $user->fullname }}" required>
        @error('fullname')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="nickname">Nickname</label>
        <input type="text" class="form-control" id="nickname" name="nickname" value="{{ $user->nickname }}" required>
        @error('nickname')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" required>
        @error('phone')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}" required>
        @error('address')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="link">Link</label>
        <input type="url" class="form-control" id="link" name="link" value="{{ $user->link }}">
        @error('link')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>
