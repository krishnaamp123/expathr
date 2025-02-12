<form action="{{ route('storeSource') }}" method="POST">
    @csrf
    <label class="kaem-text">Where do you get information related to this website, fill in just one field!</label>
        <div class="form-group">
            <label for="platform" class="kaem-subheading">Platform</label>
            <select name='platform' id="platform" class="form-control select2">
                <option value="">Select Platform</option>
                <option value="jobstreet">JobStreet</option>
                <option value="indeed">Indeed</option>
                <option value="linkedin">LinkedIn</option>
                <option value="glints">Glints</option>
                <option value="glassdoor">Glassdoor</option>
                <option value="whatsapp">Whatsapp</option>
                <option value="telegram">Telegram</option>
                <option value="facebook">Facebook</option>
                <option value="instagram">Instagram</option>
                <option value="tiktok">TikTok</option>
                <option value="google">Google</option>
            </select>
            @error('platform')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="referal" class="kaem-subheading">Referal</label>
            <input name="referal" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputReferalName">
            @error('referal')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="other" class="kaem-subheading">Other</label>
            <input name="other" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputOtherName">
            @error('other')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
