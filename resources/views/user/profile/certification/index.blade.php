    <div class="card">
        <div class="card-header" style="color: white;">Training & Certification
            @if ($certification->isEmpty())
                <p class="text-muted fst-italic kaem-text mb-0">Optional</p>
            @endif
        </div>
        <div class="card-body">
            <div class="">

                @if ($certification->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($certification as $certificationn)
                        <li class="list-group-item city-item position-relative">
                                <span class="kaem-heading">{{ $certificationn->lisence_name ?? 'Unknown Certification' }}</span><br>
                                <span class="kaem-subheading">{{ $certificationn->organization }}</span><br>
                                @if (!empty($certificationn->description))
                                    <span class="kaem-text">{{ $certificationn->description }}</span><br>
                                @endif
                                <span class="kaem-text">
                                    {{ $certificationn->start_date }} - {{ $certificationn->end_date === null ? 'Present' : $certificationn->end_date }}
                                </span><br>
                                @if (!empty($certificationn->id_credentials))
                                    <span class="kaem-text">{{ $certificationn->id_credentials }}</span><br>
                                @endif
                                @if (!empty($certificationn->url_credentials))
                                    <span class="kaem-text">{{ $certificationn->url_credentials }}</span>
                                @endif
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editCertificationModal{{ $certificationn->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyCertification', $certificationn->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No training & certification added yet.</p>
                @endif

                <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addCertificationModal">
                    Add Certification
                </button>
            </div>
        </div>
    </div>

@foreach ($certification as $certificationn)
<!-- Modal Edit Certification -->
<div class="modal fade" id="editCertificationModal{{ $certificationn->id }}" tabindex="-1" role="dialog" aria-labelledby="editCertificationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCertificationLabel">Edit Certification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCertificationForm" method="POST" action="{{ route('updateCertification', $certificationn->id) }}">
                    @csrf
                    @method('PUT')

                    <label class="kaem-text">Edit any certifications or training you have taken that are related to the job you want!</label>
                    <div class="form-group">
                        <label for="lisence_name" class="kaem-subheading">Lisence Name</label>
                        <input type="text" class="form-control kaem-sub" id="lisence_name" name="lisence_name"  value="{{ $certificationn->lisence_name }}" required>
                        @error('lisence_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="organization" class="kaem-subheading">Organization</label>
                        <input type="text" class="form-control kaem-sub" id="organization" name="organization"  value="{{ $certificationn->organization }}" required>
                        @error('organization')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="id_credentials" class="kaem-subheading">Id Credentials</label>
                        <input type="text" class="form-control kaem-sub" id="id_credentials" name="id_credentials"  value="{{ $certificationn->id_credentials }}" required>
                        @error('id_credentials')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="url_credentials" class="kaem-subheading">Url Credentials</label>
                        <input type="text" class="form-control kaem-sub" id="url_credentials" name="url_credentials"  value="{{ $certificationn->url_credentials }}" required>
                        @error('url_credentials')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="kaem-subheading">Description</label>
                        <textarea name="description" class="form-control kaem-sub" id="description" rows="5" required>{{ $certificationn->description }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="kaem-subheading">Start Date</label>
                        <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy" id="start_date" name="start_date" value="{{ $certificationn->start_date }}" required>
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="kaem-subheading">End Date</label>
                        <input type="text" class="form-control kaem-sub datepicker datepicker-mm-yyyy end-date-input"
                            id="end_date_ce{{ $certificationn->id }}"
                            name="end_date"
                            value="{{ $certificationn->end_date }}">

                        <input type="checkbox"
                            class="present-checkbox"
                            data-target="end_date_ce{{ $certificationn->id }}"
                            {{ is_null($certificationn->end_date) ? 'checked' : '' }}> Present
                    </div>

                    <button type="submit" class="btn btn-primary kaem-subheading">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Add Work Location -->
<div class="modal fade" id="addCertificationModal" tabindex="-1" role="dialog" aria-labelledby="addCertificationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCertificationLabel">Add Certification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.certification.store')
            </div>
        </div>
    </div>
</div>
