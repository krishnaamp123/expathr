@extends('user.layout.app')
@section('title', 'Profile')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="text-center">
            @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{session('message')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <h2 class="section-heading text-uppercase">Profile</h2>
            <h3 class="section-subheading mb-5">Complete your profile first before applying!</h3>
        </div>
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
            <div class="col-lg-3 mb-3">
                @include('user.profile.emergency.index', ['emergency' => $emergency])
            </div>
            <div class="col-lg-3 mb-3">
                @include('user.profile.language.index', ['language' => $language])
            </div>
            <div class="col-lg-3 mb-3">
                @include('user.profile.skill.index', ['skill' => $skill])
            </div>
            <div class="col-lg-3 mb-3">
                @include('user.profile.workfield.index', ['workfield' => $workfield, 'fields' => $fields])
            </div>
            <div class="col-lg-6 mb-3">
                @include('user.profile.education.index', ['education' => $education])
            </div>
            <div class="col-lg-6 mb-3">
                @include('user.profile.project.index', ['project' => $project])
            </div>
            <div class="col-lg-6 mb-3">
                @include('user.profile.experience.index', ['experience' => $experience])
            </div>
            <div class="col-lg-6 mb-3">
                @include('user.profile.volunteer.index', ['volunteer' => $volunteer])
            </div>
            <div class="col-lg-6 mb-3">
                @include('user.profile.organization.index', ['organization' => $organization])
            </div>
            <div class="col-lg-6 mb-3">
                @include('user.profile.certification.index', ['certification' => $certification])
            </div>
        </div>
    </div>
</section>
@endsection
