@extends('admin.layout.app')
@section('title', 'Form')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Form</h1>

    @if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{session('error')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <p class="mb-3">Master data form</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{route('addForm')}}" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="small text-center">
                            <th>ID</th>
                            <th>Form</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($forms as $row)
                        <tr class="small">
                            <td>{{$row->id}}</td>
                            <td>{{$row->form_name}}</td>
                            <td>{{ $row->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $row->updated_at->format('d-m-Y H:i:s') }}</td>
                            <td>
                                <a href="{{ route('editForm', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;">
                                    <i class="fas fa-edit"></i>
                                    {{-- Edit --}}
                                </a>
                                <form action="{{ route('destroyForm', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this form?')">
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
