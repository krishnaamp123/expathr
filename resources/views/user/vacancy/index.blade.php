@extends('user.layout.app')
@section('title', 'Job')

@section('content')

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
            <h2 class="section-heading text-uppercase">Job Vacancy</h2>
            <h3 class="section-subheading">Here are our latest job vacancies!</h3>
        </div>
        <div class="row">
            @foreach ($vacancies as $vacancy)
                <div class="col-lg-4 col-sm-6 mb-4">
                    <div class="portfolio-item">
                        <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal{{ $vacancy->id }}">
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content"></div>
                            </div>
                            <img class="img-fluid" src="{{ $vacancy->job_image ? asset($vacancy->job_image) : asset('storage/image/logopersegi.png') }}" alt="{{ $vacancy->job_name }}" />
                        </a>
                        <div class="portfolio-caption">
                            <div class="row">
                                <div class="col-7">
                                    <div class="portfolio-caption-heading">{{ $vacancy->job_name }}</div>
                                </div>
                                <div class="col-5 text-end">
                                    <div class="portfolio-caption-price">Rp {{ number_format($vacancy->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
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
                            <div class="portfolio-caption-location">{{ $vacancy->location }}</div>
                            <div class="divider"></div>
                            <div class="portfolio-caption-date">Expired: {{ $vacancy->expired }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Portfolio Modals -->
@foreach ($vacancies as $vacancy)
<!-- Modal Edit Work Location -->
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
            <p class="kaem-jobtext text-muted">{{ $vacancy->category->category_name ?? 'No Category' }}</p>
            <img class="img-fluid d-block mx-auto mb-3" src="{{ $vacancy->job_image ? asset($vacancy->job_image) : asset('storage/image/logopersegi.png') }}" alt="{{ $vacancy->job_name }}" />
            <button class="btn btn-primary kaem-subheading mb-3" data-bs-toggle="modal" data-bs-target="#applyModal{{ $vacancy->id }}">
                Apply for Job
            </button>
            <ul class="list-inline">
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-briefcase" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">{{ ucwords(str_replace('_', ' ', $vacancy->job_type)) }}</span>
                </li>
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-map-marker-alt" style="width: 20px;"></i>
                    <span class="kaem-jobtext ms-2">{{ ucwords(str_replace('_', ' ', $vacancy->location_type)) }} - {{ $vacancy->location }}</span>
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
                    <span class="kaem-jobtext ms-2">Rp {{ number_format($vacancy->price, 0, ',', '.') }}</span>
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
                        <label for="availability" class="kaem-subheading">Job Type</label>
                        <select name="availability" id="availability" class="form-control select2">
                            <option value="">Select Job Type</option>
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

