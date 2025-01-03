<form action="{{ route('storeLink') }}" method="POST">
    @csrf
    <label class="kaem-text">Social media that you have!</label>
        <div class="form-group">
            <label for="media" class="kaem-subheading">Social</label>
            <select name="media" id="media" class="form-control select2">
                <option value="">Select Social</option>
                <option value="facebook">Facebook</option>
                <option value="instagram">Instagram</option>
                <option value="x">X</option>
                <option value="linkedin">Linkedin</option>
                <option value="portofolio">Portofolio</option>
                <option value="tiktok">Tiktok</option>
            </select>
            @error('media')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="media_url" class="kaem-subheading">Url</label>
            <input name="media_url" type="url" class="form-control form-control-user"
                id="exampleInputMediaUrlName">
            @error('media_url')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
