@extends('admin.layout.app')
@section('title', 'Add Company')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Company</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Company Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeCompany') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" name="company_name" class="form-control">
                            @error('company_name')
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
