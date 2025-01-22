@extends('admin.layout.app')
@section('title', 'Offering')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Offering</h1>

   <!-- Toast Container -->
    <div aria-live="polite" aria-atomic="true" class="position-fixed" style="top: 4.5rem; right: 20rem; z-index: 1050;">
        <div id="successToast" class="toast text-white" style="background-color: #72A28A; min-width: 300px;" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true">
            <div class="toast-header text-white" style="background-color: #72A28A;">
                <strong class="mr-auto">Success</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                <!-- Pesan sukses -->
            </div>
        </div>

        <div id="failedToast" class="toast text-white" style="background-color: #c03535; min-width: 300px;" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true">
            <div class="toast-header text-white" style="background-color: #c03535;">
                <strong class="mr-auto">Error</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                <!-- Pesan gagal -->
            </div>
        </div>
    </div>

    <p class="mb-3">Add offering file here in pdf format</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div>
                <button
                    type="button"
                    class="btn btn-sm mr-1"
                    style="background-color: #72A28A; color: white;"
                    data-bs-toggle="modal"
                    data-bs-target="#addOfferingModal">
                    <i class="fas fa-plus"></i> Add
                </button>

                @include('admin.offering.storemodal', [
                    'userhrjobs' => $userhrjobs,
                ])

                <a href="{{ route('exportOffering') }}" class="btn btn-sm mr-1" style="background-color: #000; color: white;">
                    <i class="fas fa-file-excel"></i> Export All
                </a>
                <!-- Tombol Export -->
                <button class="btn btn-sm mr-1" style="background-color: #858796; color: white;" data-toggle="modal" data-target="#exportModal">
                    <i class="fas fa-calendar-check"></i> Export Date
                </button>

                <!-- Modal Popup -->
                <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exportModalLabel">Export Date Range</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('exportdateOffering') }}" method="GET">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="start_date" class="mb-0"><strong>Start Date:</strong></label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="end_date" class="mb-0"><strong>End Date:</strong></label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-share"></i> Export
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <form action="{{ route('getOffering') }}" method="GET" class="form-inline">
                    <div class="form-group mx-sm-2 mb-2">
                        <label for="start_date" class="sr-only">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date">
                    </div>
                    <span class="mx-2">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                    <div class="form-group mx-sm-2 mb-2">
                        <label for="end_date" class="sr-only">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="End Date">
                    </div>
                    <a href="{{ route('getOffering') }}" class="btn btn-secondary btn-sm mb-2 mr-2">Clear Date</a>
                    <button type="submit" class="btn btn-primary btn-sm mb-2">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="small text-center">
                            <th>ID</th>
                            <th>Job</th>
                            <th>Applicant</th>
                            <th>Offering File</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offerings as $row)
                        <tr class="small" data-id="{{ $row->id }}">
                            <td>{{ $row->id }}</td>
                            <td data-field="job_name">{{ $row->userHrjob->hrjob->job_name ?? 'No Job' }}</td>
                            <td data-field="applicant_name">{{ $row->userHrjob->user->fullname ?? 'No Applicant' }}</td>
                            <td data-field="offering_file">
                                @if($row->offering_file)
                                    <a href="{{ asset($row->offering_file) }}" target="_blank">
                                        View File
                                    </a>
                                @else
                                    No File
                                @endif
                            </td>
                            <td data-field="created_at">{{ $row->created_at }}</td>
                            <td data-field="updated_at">{{ $row->updated_at }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm my-1"
                                    style="background-color: #969696; color: white;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editOfferingModal{{ $row->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                @include('admin.offering.updatemodal', [
                                    'id' => $row->id,
                                    'offering' => $row,
                                    'userhrjobs' => $userhrjobs,
                                ])

                                <form method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm delete-btn"
                                            style="background-color: #c03535; color: white;"
                                            data-url="{{ route('destroyOffering', $row->id) }}"
                                            data-confirm-message="Are you sure you want to delete this offering?">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    const confirmMessage = this.dataset.confirmMessage || 'Are you sure you want to delete this item?';

                    if (!confirm(confirmMessage)) {
                        return;
                    }

                    const url = this.dataset.url;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    })
                        .then(response => {
                            // Simpan response untuk validasi status
                            const isOk = response.ok;
                            return response.json().then(data => ({ isOk, data }));
                        })
                        .then(({ isOk, data }) => {
                            if (isOk) {
                                const row = this.closest('tr');
                                row.remove(); // Hapus baris
                                showToast('successToast', data.message); // Tampilkan toast sukses
                            } else {
                                showToast('failedToast', data.message); // Tampilkan toast gagal
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('failedToast', 'An error occurred. Please try again.');
                        });
                });
            });

            document.querySelectorAll('.update-form-offering').forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const formData = new FormData(form); // Ambil semua input dalam form
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    // Debugging: Periksa isi FormData
                    for (const [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    fetch(form.action, {
                        method: 'POST', // Gunakan POST untuk kompatibilitas dengan FormData
                        headers: {
                            'X-CSRF-TOKEN': csrfToken, // Tambahkan token CSRF
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData, // Kirim FormData (termasuk file)
                    })
                        .then(response => {
                            const isOk = response.ok;
                            return response.json().then(data => ({ isOk, data }));
                        })
                        .then(({ isOk, data }) => {
                            console.log('Server Response:', data);
                            if (isOk) {
                                updateTableRow(data.updatedRow);
                                showToast('successToast', data.message);
                                // Tutup modal jika ada
                                if (form.closest('.modal')) {
                                    $(form.closest('.modal')).modal('hide');
                                }
                            } else {
                                showToast('failedToast', data.message || 'Failed to update offering.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('failedToast', 'An error occurred. Please try again.');
                        });
                });
            });

                function updateTableRow(updatedRow) {
                    const row = document.querySelector(`tr[data-id="${updatedRow.id}"]`);
                    if (row) {
                        Object.keys(updatedRow).forEach(key => {
                            const cell = row.querySelector(`[data-field="${key}"]`);
                            if (cell) {
                                cell.textContent = updatedRow[key];
                            }
                        });
                    }
                }

            function showToast(toastId, message) {
                const toastEl = document.getElementById(toastId);
                if (toastEl) {
                    toastEl.querySelector('.toast-body').textContent = message;
                    toastEl.classList.add('show'); // Tambahkan kelas 'show'
                    toastEl.style.pointerEvents = 'auto'; // Aktifkan pointer-events

                    setTimeout(() => {
                        toastEl.classList.remove('show'); // Hilangkan kelas 'show'
                        toastEl.style.pointerEvents = 'none'; // Matikan pointer-events
                    }, 3000); // Hilangkan setelah 3 detik
                } else {
                    console.error(`Toast element with ID ${toastId} not found`);
                }
            }

            @if(session('toast_type') && session('toast_message'))
                const toastId = "{{ session('toast_type') === 'success' ? 'successToast' : 'failedToast' }}";
                const message = "{{ session('toast_message') }}";
                showToast(toastId, message);
            @endif

        });

    </script>
