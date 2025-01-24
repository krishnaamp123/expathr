@extends('admin.layout.app')
@section('title', 'Add Placement')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Placement</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Placement Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeOutlet') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Company</label>
                            <select name="id_company" class="form-control select2">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            @error('id_company')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Placement</label>
                            <input type="text" name="outlet_name" class="form-control">
                            @error('outlet_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
