    <div class="card">
        <div class="card-header" style="color: white;">Preferred Work Location</div>
        <div class="card-body">
            <div class="kaem-subheading">

                @if ($worklocation->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($worklocation as $location)
                        <li class="list-group-item city-item position-relative">
                            <span>{{ $location->city->city_name ?? 'Unknown City' }}</span>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editWorkLocationModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyWorkLocation', $location->id) }}" method="POST" style="display: inline;">
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
                    <p>No work location added yet.</p>
                @endif
            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addWorkLocationModal">
                Add Preferred Work Location
            </button>
        </div>
        </div>
    </div>

<!-- Modal Edit Work Location -->
<div class="modal fade" id="editWorkLocationModal" tabindex="-1" role="dialog" aria-labelledby="editWorkLocationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWorkLocationLabel">Edit Work Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editWorkLocationForm" method="POST" action="{{ route('updateWorkLocation', $location->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_id_city" class="kaem-subheading">City</label>
                        <select name="id_city" id="edit_id_city" class="form-control select2" required>
                            <option value="">Select City</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary kaem-subheading">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Work Location -->
<div class="modal fade" id="addWorkLocationModal" tabindex="-1" role="dialog" aria-labelledby="addWorkLocationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWorkLocationLabel">Add Work Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.worklocation.store', ['cities' => $cities]) <!-- Include the form to edit profile -->
            </div>
        </div>
    </div>
</div>
