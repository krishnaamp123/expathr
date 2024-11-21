    <div class="card">
        <div class="card-header" style="color: white;">{{ __('Profile') }}</div>
        <div class="card-body">
            <!-- Data Profile -->
            <div class="row">
                <div class="col-md-4 kaem-subheading text-center">
                    @if ($user->profile_pict)
                        <img src="{{ asset($user->profile_pict) }}" alt="Profile Picture" class="img-fluid profile-picture">
                    @else
                        <span>Add Your Picture</span>
                    @endif

                    <div class="text-center mt-4 mb-4">
                        <button type="button" class="btn btn-primary kaem-subheading" data-toggle="modal" data-target="#editProfileModal">
                            Edit Profile
                        </button>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mb-0 kaem-heading">
                        <p>{{ $user->fullname }}</p>
                    </div>
                    <div class="mb-0 kaem-subheading"  style="margin-top: -10px;">
                        <p>{{ $user->nickname }}</p>
                    </div>
                    <div class="mb-3 kaem-text">
                        <strong>Email:</strong>
                        <p>{{ $user->email }}</p>
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
                        <strong>Birth Date / Gender:</strong>
                        <p>{{ $user->birth_date }} / {{ ucfirst($user->gender) }}</p>
                    </div>
                    <div class="mb-3 kaem-text">
                        <strong>City:</strong>
                        <p>{{ $user->city->city_name }}</p>
                    </div>
                    <div class="mb-3 kaem-text">
                        <strong>Link:</strong>
                        @if(!empty($user->link))
                            <p>{{ $user->link }}</p>
                        @else
                            <p>Add Your link</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Edit Profile -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.profileuser.update', ['cities' => $cities]) <!-- Include the form to edit profile -->
            </div>
        </div>
    </div>
</div>
