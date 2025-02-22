    <div class="card">
        <div class="card-header" style="color: white;">Language
            @if ($language->isEmpty())
                <p class="text-danger fst-italic kaem-text mb-0">Required</p>
            @endif
        </div>
        <div class="card-body">
            <div class="">

                @if ($language->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($language as $languagee)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-subheading">{{ $languagee->language ?? 'Unknown Language' }}</span><br>
                            <span class="kaem-text">{{ ucwords(str_replace('_', ' ', $languagee->skill)) }}</span><br>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editLanguageModal{{ $languagee->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyLanguage', $languagee->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="kaem-subheading">No language added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addLanguageModal">
                Add Language
            </button>
        </div>
        </div>
    </div>

@foreach ($language as $languagee)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editLanguageModal{{ $languagee->id }}" tabindex="-1" role="dialog" aria-labelledby="editLanguageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLanguageLabel">Edit Language</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editLanguageForm" method="POST" action="{{ route('updateLanguage', $languagee->id) }}">
                    @csrf
                    @method('PUT')

                    <label class="kaem-text">Edit the language you speak!</label>

                    <div class="form-group">
                        <label for="language" class="kaem-subheading">Language</label>
                        <select name="language" class="form-control select2 language-select" data-target="#language-other-{{ $languagee->id }}">
                            <option value="">Select Language</option>
                            <option value="English" {{ $languagee->language == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Indonesia" {{ $languagee->language == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                            <option value="Mandarin" {{ $languagee->language == 'Mandarin' ? 'selected' : '' }}>Mandarin</option>
                            <option value="Korean" {{ $languagee->language == 'Korean' ? 'selected' : '' }}>Korean</option>
                            <option value="Japanese" {{ $languagee->language == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                            <option value="Spanish" {{ $languagee->language == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                            <option value="Italian" {{ $languagee->language == 'Italian' ? 'selected' : '' }}>Italian</option>
                            <option value="Others" {{ !in_array($languagee->language, ['English', 'Indonesia', 'Mandarin', 'Korean', 'Japanese', 'Spanish', 'Italian']) ? 'selected' : '' }}>Others</option>
                        </select>

                        <!-- Input Text untuk Others -->
                        <input name="language" type="text" class="form-control kaem-sub form-control-user mt-2"
                               id="language-other-{{ $languagee->id }}"
                               placeholder="Type your language"
                               value="{{ !in_array($languagee->language, ['English', 'Indonesia', 'Mandarin', 'Korean', 'Japanese', 'Spanish', 'Italian']) ? $languagee->language : '' }}"
                               style="display: {{ !in_array($languagee->language, ['English', 'Indonesia', 'Mandarin', 'Korean', 'Japanese', 'Spanish', 'Italian']) ? 'block' : 'none' }};">
                        @error('language')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="skill" class="kaem-subheading">Proficiency</label>
                        <select name="skill" class="form-control select2">
                            <option value="">Select Proficiency</option>
                            <option value="basic" {{ $languagee->skill == 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="intermediate" {{ $languagee->skill == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="profesional" {{ $languagee->skill == 'profesional' ? 'selected' : '' }}>Profesional</option>
                            <option value="advanced_profesional" {{ $languagee->skill == 'advanced_profesional' ? 'selected' : '' }}>Advanced Profesional</option>
                            <option value="native" {{ $languagee->skill == 'native' ? 'selected' : '' }}>Native</option>
                        </select>
                        @error('skill')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary kaem-subheading">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Add Work Location -->
<div class="modal fade" id="addLanguageModal" tabindex="-1" role="dialog" aria-labelledby="addLanguageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLanguageLabel">Add Language</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.language.store')
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 pada semua elemen dengan class .select2
        $('.select2').select2();

        // Event Listener untuk Select2 Language
        $('.language-select').on('change', function() {
            const selectedValue = $(this).val();
            const targetInput = $($(this).data('target'));

            if (selectedValue === 'Others') {
                targetInput.show().attr('required', true).val('');
            } else {
                targetInput.hide().attr('required', false).val(selectedValue);
            }
        });

        // Trigger perubahan saat halaman dimuat untuk menampilkan input yang sudah terisi
        $('.language-select').trigger('change');
    });
</script>

