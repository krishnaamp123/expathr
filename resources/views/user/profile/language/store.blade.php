<form action="{{ route('storeLanguage') }}" method="POST">
    @csrf
        <div class="form-group">
            <label for="language" class="kaem-subheading">Language</label>
            <input name="language" type="text" class="form-control form-control-user"
                id="exampleInputLanguageName">
            @error('language')
                <div class="custom-text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="skill" class="kaem-subheading">Skill</label>
            <select name="skill" id="search-bar" class="form-control select2">
                <option value="">Select Skill</option>
                <option value="basic">Basic</option>
                <option value="intermediate">Intermediate</option>
                <option value="profesional">Profesional</option>
                <option value="advanced_profesional">Advanced Profesional</option>
                <option value="native">Native</option>
            </select>
            @error('skill')
                <div class="custom-text-danger">{{ $message }}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
