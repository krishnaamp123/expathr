@extends('user.layout.app')
@section('title', 'Job')

@section('content')
<section class="page-section" id="portfolio">
    <div class="container">
        <div class="text-center">
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
                                    <div class="portfolio-caption-type"> {{ ucwords(str_replace('_', ' ', $vacancy->job_type)) }}</div>
                                </div>
                                <div class="col-auto">
                                    <div class="portfolio-caption-type"> {{ ucwords(str_replace('_', ' ', $vacancy->location_type)) }}</div>
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
@endsection
