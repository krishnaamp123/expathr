<form action="{{ route('storeAbout') }}" method="POST">
    @csrf
        <div class="form-group">
            <label for="about" class="kaem-subheading">About</label>
            <textarea name="about" class="form-control form-control-user" id="exampleInputAbout" rows="5"></textarea>
            @error('about')
                <div class="custom-text-danger">{{$message}}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
