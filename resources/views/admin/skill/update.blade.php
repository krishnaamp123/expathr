@extends('admin.layout.app')
@section('title', 'Edit Skill')
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Skill</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Update Skill Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('updateSkill', $skill->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Skill</label>
                            <input type="text" name="skill_name" class="form-control" value="{{ old('skill_name', $skill->skill_name) }}">
                            @error('skill_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('getSkill') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
