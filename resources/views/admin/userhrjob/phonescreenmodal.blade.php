<!-- phoneScreenModal.blade.php -->
<div class="modal fade" id="phoneScreenModal" tabindex="-1" role="dialog" aria-labelledby="phoneScreenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phoneScreenModalLabel">Add Phone Screen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <form action="{{ route('storePhoneScreen') }}" method="POST">
                        @csrf
                    <div class="form-group">
                        <label>Applicant</label>
                        <input
                            type="text"
                            name="fullname"
                            class="form-control"
                            id="modalUserJobName"
                            value=""
                            readonly
                        >
                        <input
                            type="hidden"
                            name="id_user_job"
                            id="modalUserJobId"
                            value=""
                        >
                    </div>
                    <div class="form-group">
                        <label>Phone Screen Date</label>
                        <input type="text" name="phonescreen_date" class="form-control datepicker datepicker-input">
                        @error('phonescreen_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Phone Screen Time</label>
                        <input type="time" name="time" class="form-control">
                        @error('time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
                </form>
                </div>
        </div>
    </div>
</div>

