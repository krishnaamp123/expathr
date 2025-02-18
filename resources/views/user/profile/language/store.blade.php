<form action="{{ route('storeLanguage') }}" method="POST">
    @csrf
    <label class="kaem-text">Add the language you speak!</label>
        <div class="form-group">
            <label for="language" class="kaem-subheading">Language</label>
            <select name="language" id="language-select" class="form-control select2">
                <option value="">Select Language</option>
                <option value="English">English</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Mandarin">Mandarin</option>
                <option value="Korean">Korean</option>
                <option value="Japanese">Japanese</option>
                <option value="Spanish">Spanish</option>
                <option value="Italian">Italian</option>
                <option value="Others">Others</option>
            </select>
            <input name="language" type="text" class="form-control kaem-sub form-control-user mt-2"
                   id="language-other" placeholder="Type your language" style="display: none;">
            @error('language')
                <div class="text-danger">{{ $message }}</div>
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

<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('#language-select').select2();

        // Event Listener untuk perubahan di Select2
        $('#language-select').on('change', function() {
            const selectedValue = $(this).val();
            const otherInput = $('#language-other');

            if (selectedValue === 'Others') {
                otherInput.show().attr('required', true).val('');
            } else {
                otherInput.hide().attr('required', false).val(selectedValue);
            }
        });
    });
</script>

