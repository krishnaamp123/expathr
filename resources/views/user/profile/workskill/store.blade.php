<form action="{{ route('storeWorkSkill') }}" method="POST">
    @csrf
    <label class="kaem-text">Select all the skills that you master!</label>
        <div class="form-group">
            <label for="id_skill" class="kaem-subheading">Skill</label>
            <select name="id_skill" id="id_skill" class="form-control select2" required>
                <option value="">Select Skill</option>
                @foreach ($skills as $skill)
                    <option value="{{ $skill->id }}">{{ $skill->skill_name }}</option>
                @endforeach
            </select>
        </div>
    <button type="submit" class="btn btn-primary kaem-subheading">Add</button>
</form>
