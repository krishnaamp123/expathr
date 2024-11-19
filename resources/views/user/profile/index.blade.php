@extends('user.layout.app')
@section('title', 'Profile')

@section('content')
<section class="page-section" id="team">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card ">
                <div class="card-header"  style="color: white;">{{ __('Profile') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if ($user->profile_pict)
                                <img src="{{ asset($user->profile_pict) }}" alt="Profile Picture" class="img-fluid rounded-circle" width="150" height="150">
                            @else
                                <span>No Picture</span>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3 kaem-text">
                                <strong>Fullname:</strong>
                                <p>{{ $user->fullname }}</p>
                            </div>
                            <div class="mb-3 kaem-text">
                                <strong>Email:</strong>
                                <p>{{ $user->email }}</p>
                            </div>
                            <div class="mb-3 kaem-text">
                                <strong>Nickname:</strong>
                                <p>{{ $user->nickname }}</p>
                            </div>
                            <div class="mb-3 kaem-text">
                                <strong>Phone:</strong>
                                <p>{{ $user->phone }}</p>
                            </div>
                            <div class="mb-3 kaem-text">
                                <strong>Address:</strong>
                                <p>{{ $user->address }}</p>
                            </div>
                            <div class="mb-3 kaem-text">
                                <strong>City:</strong>
                                <p>{{ $user->city->city_name }}</p>
                            </div>
                            <div class="mb-3 kaem-text">
                                <strong>Link:</strong>
                                <p>{{ $user->link }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.update', ['cities' => $cities])
            </div>
        </div>
    </div>
</div>
@endsection
