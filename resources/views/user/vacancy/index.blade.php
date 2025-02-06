@extends('user.layout.app')
@section('title', 'Job')

@section('content')

<div class="page-wrapper">
<section class="page-section" id="portfolio">
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
            <h2 class="section-heading text-uppercase-libre">All Job</h2>
            <h3 class="section-subheading">Here are all our job openings!</h3>
            <h3 class="section-subheading-kaem mb-5">Make sure you have completed your profile first!</h3>
        </div>

        <div class="row">
            <div class="col-md-5 mb-4">
                <label for="searchBar" class="kaem-subheading">Search by Position</label>
                <div class="input-group-kaem">
                    <!-- Ikon Pencarian -->
                    <span class="input-icon-kaem">
                        <i class="fas fa-search"></i>
                    </span>
                    <!-- Input Pencarian -->
                    <input type="text" id="searchBar" class="form-control-kaem" placeholder="Search jobs...">
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <label for="cityFilter" class="kaem-subheading">Search by City</label>
                <select id="cityFilter" class="form-control select2">
                    <option value="">Select City</option>
                    @foreach ($vacancies->pluck('city')->unique('id')->filter() as $city)
                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-4">
                <label for="categoryFilter" class="kaem-subheading">Search by Category</label>
                <select id="categoryFilter" class="form-control select2">
                    <option value="">Select Category</option>
                    @foreach ($vacancies->pluck('category')->unique('id')->filter() as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 mb-4">
                <label for="button" class="kaem-subheading">&nbsp;</label>
                <button type="button" class="btn btn-secondary" onclick="resetFilters()">&nbsp;Clear&nbsp;</button>
            </div>
        </div>

        <div class="row">
            @foreach ($vacancies as $vacancy)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 portfolio-item mb-4" data-city-id="{{ $vacancy->id_city }}" data-category-id="{{ $vacancy->id_category }}" data-bs-toggle="modal" href="#portfolioModal{{ $vacancy->id }}">
                        <div class="portfolio-caption">
                            <div class="portfolio-caption-heading">{{ $vacancy->job_name }}</div>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="portfolio-caption-type">{{ ucwords(str_replace('_', ' ', $vacancy->job_type)) }}</div>
                                </div>
                                <div class="col-auto">
                                    <div class="portfolio-caption-type">{{ ucwords(str_replace('_', ' ', $vacancy->location_type)) }}</div>
                                </div>
                                <div class="col-auto">
                                    <div class="portfolio-caption-type">Minimum {{ $vacancy->education_min }}</div>
                                </div>
                                <div class="col-auto">
                                    <div class="portfolio-caption-type">{{ $vacancy->experience_min }} Year</div>
                                </div>
                            </div>
                            <div class="portfolio-caption-location">{{ $vacancy->city->city_name }}</div>
                            <p class="kaem-jobtext text-muted mb-0">{{ $vacancy->category->category_name ?? 'No Category' }}</p>
                            <div class="divider"></div>
                            <div class="portfolio-caption-date">
                                Expired: {{ $vacancy->expired }}
                                @if ($vacancy->is_ended === 'yes')
                                    <span class="text-danger ms-2">Ended</span>
                                @endif
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</section>
</div>

<!-- Vacancies Modals -->
@foreach ($vacancies as $vacancy)
<div class="modal fade" id="portfolioModal{{ $vacancy->id }}" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Job</h5>
            <button type="button" class="close" data-bs-dismiss="modal"aria-label="Close" style="color: white">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <!-- Job details -->
            <h2 class="kaem-jobtitle">{{ $vacancy->job_name }}</h2>
            <p class="kaem-jobtext text-muted mb-1">{{ $vacancy->category->category_name ?? 'No Category' }}</p>
            <button class="btn btn-primary kaem-subheading mb-3"
                @if (!$isProfileComplete)
                    onclick="window.location.href='{{ route('getProfile') }}'"
                    title="Please complete your profile to apply for this job."
                @elseif (auth()->user() && auth()->user()->hasAppliedFor($vacancy->id))
                    disabled
                @elseif ($vacancy->is_ended === 'yes')
                    disabled
                    title="This job has ended."
                @else
                    data-bs-toggle="modal" data-bs-target="#applyModal{{ $vacancy->id }}"
                @endif>

                @if (!$isProfileComplete)
                    Complete Your Profile
                @elseif (auth()->user() && auth()->user()->hasAppliedFor($vacancy->id))
                    Already Applied
                @elseif ($vacancy->is_ended === 'yes')
                    Job Ended
                @else
                    Apply for Job
                @endif
            </button>
            <ul class="list-inline">
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-briefcase" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">{{ ucwords(str_replace('_', ' ', $vacancy->job_type)) }}</span>
                </li>
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-map-marker-alt" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">{{ ucwords(str_replace('_', ' ', $vacancy->location_type)) }} - {{ $vacancy->city->city_name }} - {{ $vacancy->outlet->outlet_name }}</span>
                </li>
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-graduation-cap" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">Minimum {{ $vacancy->education_min }}</span>
                </li>
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-briefcase" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">{{ $vacancy->experience_min }} Years</span>
                </li>
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-money-bill-wave" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">
                        @if (!empty($vacancy->price))
                            @if(!$vacancy->hide_salary)
                                Rp {{ number_format($vacancy->price, 0, ',', '.') }}
                            @else
                                Confidential
                            @endif
                        @else
                            -
                        @endif
                    </span>
                </li>
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-calendar-alt" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">{{ $vacancy->expired }}</span>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <i class="fas fa-users" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">{{ $vacancy->number_hired }} Person</span>
                </li>
                <li class="mb-2 kaem-jobtext">
                    <strong>Description :</strong>
                    <p>{{ $vacancy->description }}</p>
                </li>
                <li class="mb-2 kaem-jobtext">
                    <strong>Qualification :</strong>
                    <p>{{ $vacancy->qualification }}</p>
                </li>
                <li class="mb-2 kaem-jobtext">
                    <strong>Job Report :</strong>
                    <p>{{ $vacancy->job_report }}</p>
                </li>
            </ul>
        </div>
    </div>
</div>
</div>

<!-- Modal Apply for Job -->
<div class="modal fade" id="applyModal{{ $vacancy->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply for Job</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('storeVacancy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_job" value="{{ $vacancy->id }}">

                    <div class="form-group">
                        <label for="salary_expectation" class="kaem-subheading">Salary Expectation</label>
                        <input name="salary_expectation" type="number" class="form-control form-control-user"
                            id="exampleInputSalaryExpectation">
                        @error('salary_expectation')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="availability" class="kaem-subheading">Availability</label>
                        <select name="availability" class="form-control select2">
                            <option value="">Select Availability</option>
                            <option value="immediately">Immediately</option>
                            <option value="<1_month_notice">< 1 Month Notice</option>
                            <option value="1_month_notice">1 Month Notice</option>
                            <option value=">1_month_notice">> 1 Month Notice</option>
                        </select>
                        @error('availability')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary kaem-subheading">Submit Application</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    .portfolio-item {
        cursor: pointer; /* Mengubah kursor menjadi pointer saat berada di atas elemen portfolio-item */
    }

    .page-wrapper {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
    }
</style>
@endsection
