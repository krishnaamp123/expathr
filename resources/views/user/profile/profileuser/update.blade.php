<form method="POST" action="{{ route('updateProfile', $user->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label class="kaem-subheading">Current Profile Picture</label><br>
        @if ($user->profile_pict)
            <img src="{{ asset($user->profile_pict) }}" alt="User Image" class="profile-picture-edit">
        @else
            <span class="kaem-text">No Picture Available</span>
        @endif
    </div>

    <div class="form-group">
        <label class="kaem-subheading">New Profile Picture</label>
        <input type="file" name="file" class="form-control">
        @error('file')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="kaem-subheading">Domicile City</label>
            <select name="id_city" class="form-control select2">
                <option value="">Select Domicile City</option>
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
        <label for="fullname" class="kaem-subheading">Fullname</label>
        <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $user->fullname }}" required>
        @error('fullname')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="nickname" class="kaem-subheading">Nickname</label>
        <input type="text" class="form-control" id="nickname" name="nickname" value="{{ $user->nickname }}" required>
        @error('nickname')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="phone" class="kaem-subheading">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" required>
        @error('phone')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="address" class="kaem-subheading">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}" required>
        @error('address')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="birth_date" class="kaem-subheading">Birth Date</label>
        <input type="text" class="form-control datepicker datepicker-yyyy-mm-dd" id="birth_date" name="birth_date" value="{{ $user->birth_date }}" required>
        @error('birth_date')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="gender" class="kaem-subheading">Gender</label>
        <select name="gender" class="form-control select2">
            <option value="">Select Gender</option>
            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
        </select>
        @error('gender')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="link" class="kaem-subheading">Link</label>
        <input type="url" class="form-control" id="link" name="link" value="{{ $user->link }}">
        @error('link')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary kaem-subheading">Update Profile</button>
</form>
