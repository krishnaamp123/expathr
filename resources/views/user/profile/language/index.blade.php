    <div class="card">
        <div class="card-header" style="color: white;">Language</div>
        <div class="card-body">
            <div class="">

                @if ($language->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($language as $languagee)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-subheading">{{ $languagee->language ?? 'Unknown Language' }}</span><br>
                            <span class="kaem-text">{{ $languagee->skill }}</span><br>
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
                <form id="editLanguageForm" method="POST" action="{{ route('updateLanguage', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="language" class="kaem-subheading">Language</label>
                        <input type="text" class="form-control" id="language" name="language"  value="{{ $languagee->language }}" required>
                        @error('language')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="skill" class="kaem-subheading">Skill</label>
                        <select name="skill" class="form-control select2">
                            <option value="">Select Skill</option>
                            <option value="basic" {{ $languagee->language == 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="intermediete" {{ $languagee->language == 'intermediete' ? 'selected' : '' }}>Intermediete</option>
                            <option value="profesional" {{ $languagee->language == 'profesional' ? 'selected' : '' }}>Profesional</option>
                            <option value="advanced_profesional" {{ $languagee->language == 'advanced_profesional' ? 'selected' : '' }}>Advanced Profesional</option>
                            <option value="native" {{ $languagee->language == 'native' ? 'selected' : '' }}>Native</option>
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