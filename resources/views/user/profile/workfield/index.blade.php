    <div class="card">
        <div class="card-header" style="color: white;">Preferred Work Field</div>
        <div class="card-body">
            <div class="kaem-subheading">

                @if ($workfield->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($workfield as $workfieldd)
                        <li class="list-group-item city-item position-relative">
                            <span>{{ $workfieldd->field->field_name ?? 'Unknown Field' }}</span>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editWorkFieldModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyWorkField', $workfieldd->id) }}" method="POST" style="display: inline;">
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
                    <p>No work field added yet.</p>
                @endif
            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addWorkFieldModal">
                Add Preferred Work Field
            </button>
        </div>
        </div>
    </div>

<!-- Modal Edit Work Field -->
@foreach ($workfield as $workfieldd)
<div class="modal fade" id="editWorkFieldModal" tabindex="-1" role="dialog" aria-labelledby="editWorkFieldLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWorkFieldLabel">Edit Work Field</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editWorkFieldForm" method="POST" action="{{ route('updateWorkField', $workfieldd->id) }}">
                    @csrf
                    @method('PUT')
                    <label class="kaem-text">Select all the fields you want to place!</label>
                    <div class="form-group">
                        <label for="id_field" class="kaem-subheading">Field</label>
                        <select name="id_field" id="edit_id_field" class="form-control select2" required>
                            <option value="">Select Field</option>
                            @foreach ($fields as $field)
                                <option value="{{ $field->id }}">{{ $field->field_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary kaem-subheading">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Add Work Field -->
<div class="modal fade" id="addWorkFieldModal" tabindex="-1" role="dialog" aria-labelledby="addWorkFieldLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWorkFieldLabel">Add Work Field</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.workfield.store', ['fields' => $fields]) <!-- Include the form to edit profile -->
            </div>
        </div>
    </div>
</div>
