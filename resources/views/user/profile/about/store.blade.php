<form action="{{ route('storeAbout') }}" method="POST">
    @csrf
    <label class="kaem-text">Write a summary that describes yourself, minimum 50 characters!</label>
        <div class="form-group">
            <label for="about" class="kaem-subheading">About</label>
            <textarea name="about" class="form-control kaem-sub form-control-user" id="exampleInputAbout" rows="5"></textarea>
            @error('about')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
