    <div class="card">
        <div class="card-header" style="color: white;">Emergency Contact</div>
        <div class="card-body">
            <div class="">

                @if ($emergency->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($emergency as $emergencyy)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-subheading">{{ $emergencyy->emergency_name ?? 'Unknown Emergency Contact' }}</span><br>
                            <span class="kaem-text">{{ $emergencyy->emergency_relation }}</span><br>
                            <span class="kaem-text">{{ $emergencyy->emergency_phone }}</span>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editEmergencyModal{{ $emergencyy->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyEmergency', $emergencyy->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No emergency contact added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addEmergencyModal">
                Add Emergency Contact
            </button>
        </div>
        </div>
    </div>

@foreach ($emergency as $emergencyy)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editEmergencyModal{{ $emergencyy->id }}" tabindex="-1" role="dialog" aria-labelledby="editEmergencyLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmergencyLabel">Edit Emergency Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editEmergencyForm" method="POST" action="{{ route('updateEmergency', $emergencyy->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="emergency_name" class="kaem-subheading">Name</label>
                        <input type="text" class="form-control" id="emergency_name" name="emergency_name"  value="{{ $emergencyy->emergency_name }}" required>
                        @error('emergency_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="emergency_relation" class="kaem-subheading">Relation</label>
                        <input type="text" class="form-control" id="emergency_relation" name="emergency_relation" value="{{ $emergencyy->emergency_relation }}" required>
                        @error('emergency_relation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="emergency_phone" class="kaem-subheading">Phone</label>
                        <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" value="{{ $emergencyy->emergency_phone }}" required>
                        @error('emergency_phone')
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
<div class="modal fade" id="addEmergencyModal" tabindex="-1" role="dialog" aria-labelledby="addEmergencyLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmergencyLabel">Add Emergency Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.emergency.store')
            </div>
        </div>
    </div>
</div>
