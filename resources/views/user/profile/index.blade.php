@extends('user.layout.app')
@section('title', 'Profile')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="row">
            <!-- Include Profile Section -->
            <div class="col-lg-6 mb-3">
                @include('user.profile.profileuser.index', ['user' => $user, 'cities' => $cities])
            </div>
            <div class="col-lg-6 mb-3">
                @include('user.profile.worklocation.index', ['worklocation' => $worklocation, 'cities' => $cities])
                <hr class="my-2">
                @include('user.profile.about.index', ['about' => $about])
            </div>
            <div class="col-lg-6 mb-3">
                @include('user.profile.emergency.index', ['emergency' => $emergency])
            </div>
        </div>
    </div>
</section>
@endsection
