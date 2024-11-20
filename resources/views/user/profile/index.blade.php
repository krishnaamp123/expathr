@extends('user.layout.app')
@section('title', 'Profile')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="row">
            <!-- Include Profile Section -->
            @include('user.profile.profileuser.index', ['user' => $user, 'cities' => $cities])
            @include('user.profile.worklocation.index', ['worklocation' => $worklocation, 'cities' => $cities])
        </div>
    </div>
</section>
@endsection
