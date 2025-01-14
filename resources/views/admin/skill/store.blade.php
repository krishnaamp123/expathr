@extends('admin.layout.app')
@section('title', 'Add Skill')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Skill</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Add Skill Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeSkill') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label>Skill</label>
                            <input type="text" name="skill_name" class="form-control">
                            @error('skill_name')
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
