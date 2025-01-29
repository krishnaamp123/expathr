<form action="{{ route('storeLanguage') }}" method="POST">
    @csrf
    <label class="kaem-text">Add the language you speak!</label>
        <div class="form-group">
            <label for="language" class="kaem-subheading">Language</label>
            <input name="language" type="text" class="form-control kaem-sub form-control-user"
                id="exampleInputLanguageName">
            @error('language')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="skill" class="kaem-subheading">Proficiency</label>
            <select name="skill" id="skill" class="form-control select2">
                <option value="">Select Proficiency</option>
                <option value="basic">Basic</option>
                <option value="intermediate">Intermediate</option>
                <option value="profesional">Profesional</option>
                <option value="advanced_profesional">Advanced Profesional</option>
                <option value="native">Native</option>
            </select>
            @error('skill')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
