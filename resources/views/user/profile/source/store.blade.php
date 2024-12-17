<form action="{{ route('storeSource') }}" method="POST">
    @csrf

        <div class="form-group">
            <label for="platform" class="kaem-subheading">Platform</label>
            <select name='platform' id="platform" class="form-control select2">
                <option value="">Select Platform</option>
                <option value="linkedin">LinkedIn</option>
                <option value="indeed">Indeed</option>
                <option value="glassdoor">Glassdoor</option>
                <option value="jobstreet">JobStreet</option>
                <option value="kalibrr">Kalibrr</option>
                <option value="glints">Glints</option>
                <option value="instagram">Instagram</option>
                <option value="loker.id">Loker.id</option>
                <option value="monster">Monster</option>
                <option value="fiverr">Fiverr</option>
                <option value="upwork">Upwork</option>
                <option value="urbanhire">Urbanhire</option>
                <option value="karir.com">Karir.com</option>
            </select>
            @error('platform')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="referal" class="kaem-subheading">Referal</label>
            <input name="referal" type="text" class="form-control form-control-user"
                id="exampleInputReferalName">
            @error('referal')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="other" class="kaem-subheading">Other</label>
            <input name="other" type="text" class="form-control form-control-user"
                id="exampleInputOtherName">
            @error('other')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
