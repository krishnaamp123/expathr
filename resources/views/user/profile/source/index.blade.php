<div class="card">
    <div class="card-header" style="color: white;">Information Source</div>
    <div class="card-body">
        <div class="">
            @if ($source->isNotEmpty())
                <ul class="list-group">
                    @foreach ($source as $sourcee)
                        <li class="list-group-item city-item position-relative">
                            @if (!empty($sourcee->platform))
                                <span class="kaem-subheading">Platform: {{ ucwords(str_replace('_', ' ', $sourcee->platform)) }}</span>
                            @elseif (!empty($sourcee->referal))
                                <span class="kaem-subheading">Referal: {{ $sourcee->referal }}</span>
                            @elseif (!empty($sourcee->other))
                                <span class="kaem-subheading">Other: {{ $sourcee->other }}</span>
                            @endif

                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">
                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editSourceModal-{{ $sourcee->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroySource', $sourcee->id) }}" method="POST" style="display: inline;">
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
                <p class="kaem-subheading">No source added yet.</p>
            @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addSourceModal">
                Add Source
            </button>
        </div>
    </div>
</div>

@foreach ($source as $sourcee)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editSourceModal-{{ $sourcee->id }}" tabindex="-1" role="dialog" aria-labelledby="editSourceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSourceLabel">Edit Information Source</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSourceForm" method="POST" action="{{ route('updateSource', $sourcee->id) }}">
                    @csrf
                    @method('PUT')

                    <label class="kaem-text">Where do you get information related to this website, fill in just one field!</label>
                    <div class="form-group">
                        <label for="editplatform" class="kaem-subheading">Platform</label>
                        <select name="platform" class="form-control select2 editplatform">
                            <option value="">Select Platform</option>
                            <option value="linkedin" {{ $sourcee->platform == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                            <option value="indeed" {{ $sourcee->platform == 'indeed' ? 'selected' : '' }}>Indeed</option>
                            <option value="glassdoor" {{ $sourcee->platform == 'glassdoor' ? 'selected' : '' }}>Glassdoor</option>
                            <option value="jobstreet" {{ $sourcee->platform == 'jobstreet' ? 'selected' : '' }}>JobStreet</option>
                            <option value="kalibrr" {{ $sourcee->platform == 'kalibrr' ? 'selected' : '' }}>Kalibrr</option>
                            <option value="glints" {{ $sourcee->platform == 'glints' ? 'selected' : '' }}>Glints</option>
                            <option value="instagram" {{ $sourcee->platform == 'instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="loker.id" {{ $sourcee->platform == 'loker.id' ? 'selected' : '' }}>Loker.id</option>
                            <option value="monster" {{ $sourcee->platform == 'monster' ? 'selected' : '' }}>Monster</option>
                            <option value="fiverr" {{ $sourcee->platform == 'fiverr' ? 'selected' : '' }}>Fiverr</option>
                            <option value="upwork" {{ $sourcee->platform == 'upwork' ? 'selected' : '' }}>Upwork</option>
                            <option value="urbanhire" {{ $sourcee->platform == 'urbanhire' ? 'selected' : '' }}>Urbanhire</option>
                            <option value="karir.com" {{ $sourcee->platform == 'karir.com' ? 'selected' : '' }}>Karir.com</option>
                        </select>
                        @error('editplatform')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="referal" class="kaem-subheading">Referal</label>
                        <input type="text" class="form-control kaem-sub" id="referal" name="referal"  value="{{ $sourcee->referal }}">
                        @error('referal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="other" class="kaem-subheading">Other</label>
                        <input type="text" class="form-control kaem-sub" id="other" name="other"  value="{{ $sourcee->other }}">
                        @error('other')
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
<div class="modal fade" id="addSourceModal" tabindex="-1" role="dialog" aria-labelledby="addSourceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSourceLabel">Add Information Source</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.source.store')
            </div>
        </div>
    </div>
</div>
