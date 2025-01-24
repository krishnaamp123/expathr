@extends('admin.layout.app')
@section('title', 'Placement')
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Placement</h1>

    @if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <p class="mb-3">Master data placement</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{route('addOutlet')}}" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="small text-center">
                            <th>ID</th>
                            <th>Company</th>
                            <th>Placement</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outlets as $row)
                        <tr class="small">
                            <td>{{$row->id}}</td>
                            <td>{{$row->company->company_name}}</td>
                            <td>{{$row->outlet_name}}</td>
                            <td>{{ $row->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $row->updated_at->format('d-m-Y H:i:s') }}</td>
                            <td>
                                <a href="{{ route('editOutlet', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;">
                                    <i class="fas fa-edit"></i>
                                    {{-- Edit --}}
                                </a>
                                <form action="{{ route('destroyOutlet', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this outlet?')">
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
