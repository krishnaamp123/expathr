    <div class="card">
        <div class="card-header" style="color: white;">Reference Contact</div>
        <div class="card-body">
            <div class="">

                @if ($reference->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($reference as $referencee)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-subheading">{{ $referencee->reference_name ?? 'Unknown Reference Contact' }}</span><br>
                            <span class="kaem-text">{{ $referencee->relation }} - {{ $referencee->company_name }}</span><br>
                            <span class="kaem-text">{{ $referencee->phone }} - {{ ucwords(str_replace('_', ' ', $referencee->is_call)) }}</span>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editReferenceModal{{ $referencee->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyReference', $referencee->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No reference contact added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addReferenceModal">
                Add Reference Contact
            </button>
        </div>
        </div>
    </div>

@foreach ($reference as $referencee)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editReferenceModal{{ $referencee->id }}" tabindex="-1" role="dialog" aria-labelledby="editReferenceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReferenceLabel">Edit Reference Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editReferenceForm" method="POST" action="{{ route('updateReference', $referencee->id) }}">
                    @csrf
                    @method('PUT')
                    <label class="kaem-text">Contact references must added at least 2 contacts!</label>
                    <div class="form-group">
                        <label for="reference_name" class="kaem-subheading">Reference Name</label>
                        <input type="text" class="form-control kaem-sub" id="reference_name" name="reference_name"  value="{{ $referencee->reference_name }}" required>
                        @error('reference_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="relation" class="kaem-subheading">Relation</label>
                        <input type="text" class="form-control kaem-sub" id="relation" name="relation" value="{{ $referencee->relation }}" required>
                        @error('relation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="company_name" class="kaem-subheading">Company Name</label>
                        <input type="text" class="form-control kaem-sub" id="company_name" name="company_name"  value="{{ $referencee->company_name }}" required>
                        @error('company_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="kaem-subheading">Phone</label>
                        <input type="text" class="form-control kaem-sub" id="phone" name="phone" value="{{ $referencee->phone }}" required>
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="is_call" class="kaem-subheading">Can Be Contacted?</label>
                        <select name="is_call" class="form-control select2">
                            <option value="">Select</option>
                            <option value="yes" {{ $referencee->is_call == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ $referencee->is_call == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('is_call')
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
<div class="modal fade" id="addReferenceModal" tabindex="-1" role="dialog" aria-labelledby="addReferenceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReferenceLabel">Add Reference Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.reference.store')
            </div>
        </div>
    </div>
</div>
