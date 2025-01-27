@extends('admin.layout.app')
@section('title', 'Job')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Job</h1>

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

    <p class="mb-3">Master data job</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{route('addHrjob')}}" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="small text-center">
                            <th>ID</th>
                            <th>Job Category</th>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            {{-- <th>Job Report</th>
                            <th>Salary</th>
                            <th>Description</th>
                            <th>Qualification</th>
                            <th>Location Type</th> --}}
                            <th>Location</th>
                            <th>Placement</th>
                            {{-- <th>Experience Min</th>
                            <th>Education Min</th>
                            <th>Number Hired</th> --}}
                            <th>Is Ended</th>
                            {{-- <th>Hiring Cost</th> --}}
                            <th>Created At</th>
                            <th>Expired</th>
                            <th>Job Closed</th>
                            {{-- <th>Updated At</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hrjobs as $row)
                        <tr class="small" data-id="{{ $row->id }}">
                            <td>{{$row->id}}</td>
                            <td data-field="category_name">{{$row->category->category_name ?? 'No Category'}}</td>
                            <td data-field="job_name">
                                <a href="{{ route('getUserHrjob', ['id_job' => $row->id]) }}" class="btn p-0" style="font-size: 0.8rem;">
                                    {{$row->job_name}}
                                </a>
                            </td>
                            <td data-field="job_type">{{ ucwords(str_replace('_', ' ', $row->job_type)) }}</td>
                            {{-- <td>{{$row->job_report}}</td>
                            <td data-job="{{ $row->price }}">Rp {{ number_format($row->price, 0, ',', '.') }}</td>
                            <td>{{$row->description}}</td>
                            <td>{{$row->qualification}}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $row->location_type)) }}</td> --}}
                            <td data-field="city_name">{{$row->city->city_name ?? 'No City'}}</td>
                            <td data-field="outlet_name">{{$row->outlet->outlet_name ?? 'No Outlet'}}</td>
                            {{-- <td>{{$row->experience_min}}</td>
                            <td>{{$row->education_min}}</td>
                            <td>{{$row->number_hired}}</td> --}}
                            <td data-field="is_ended">{{$row->is_ended}}</td>
                            {{-- <td>{{$row->hiring_cost ?? 'No Cost'}}</td> --}}
                            <td data-field="created_at">{{ $row->created_at->format('d-m-Y H:i:s') }}</td>
                            <td data-field="expired">
                                @if ($row->expired)
                                    {{ \Carbon\Carbon::parse($row->expired)->format('d-m-Y') }}
                                @else
                                    Not Expired
                                @endif
                            </td>
                            <td data-field="job_closed">
                                @if ($row->job_closed)
                                    {{ \Carbon\Carbon::parse($row->job_closed)->format('d-m-Y H:i:s') }}
                                @else
                                    Not Closed
                                @endif
                            </td>
                            {{-- <td>{{$row->updated_at}}</td> --}}
                            <td>
                                <a href="{{ route('editHrjob', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;">
                                    <i class="fas fa-edit"></i>
                                    {{-- Edit --}}
                                </a>
                                <button
                                    type="button"
                                    class="btn btn-sm my-1"
                                    style="background-color: #72A28A; color: white;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editIsEndedModal{{ $row->id }}">
                                    <i class="fas fa-tag"></i>
                                </button>

                                @include('admin.hrjob.isendedmodal', [
                                    'id' => $row->id,
                                    'hrjobs' => $row,
                                    'offerings' => $offerings,
                                ])

                                <form action="{{ route('destroyHrjob', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this job?')">
                                        <i class="fas fa-trash"></i>
                                        {{-- Delete --}}
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
            document.querySelectorAll('.update-form').forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const form = event.target;
                    const formData = new FormData(form);

                    // Ambil semua data dari form
                    const formDataObject = Object.fromEntries(formData.entries());
                    formDataObject.selected_offerings = formData.getAll('selected_offerings[]'); // Ambil array dari multiple select

                    console.log('Data yang akan dikirim:', formDataObject);

                    const url = form.action;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formDataObject), // Kirim data dalam format JSON
                    })
                        .then(response => {
                            const isOk = response.ok;
                            return response.json().then(data => ({ isOk, data }));
                        })
                        .then(({ isOk, data }) => {
                            if (isOk) {
                                updateTableRow(data.updatedRow); // Fungsi untuk update tabel
                                showToast('successToast', data.message); // Tampilkan notifikasi sukses

                                // Tutup modal
                                const modal = form.closest('.modal');
                                if (modal) {
                                    $(modal).modal('hide');
                                }
                            } else {
                                console.error('Error Response:', data);
                                showToast('failedToast', data.message); // Tampilkan notifikasi error
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error.message || error);
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
