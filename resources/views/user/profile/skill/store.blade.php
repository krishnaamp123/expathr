<form action="{{ route('storeSkill') }}" method="POST">
    @csrf
        <div class="form-group">
            <label for="skill_name" class="kaem-subheading">Skill Name</label>
            <input name="skill_name" type="text" class="form-control form-control-user"
                id="exampleInputSkillName">
            @error('skill_name')
                <div class="text-danger">{{$message}}</div>
            @enderror
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
