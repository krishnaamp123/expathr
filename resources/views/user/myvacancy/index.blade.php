@extends('user.layout.app')
@section('title', 'My Job')

@section('content')

<section class="page-section" id="portfolio">
    <div class="container">
        <div class="text-center">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <h2 class="section-heading text-uppercase">My Job</h2>
            <h3 class="section-subheading mb-5">Here are all your applications!</h3>
        </div>
    </div>
    <div class="container d-flex">
        <!-- Filter Status (Kiri) -->
        <div class="filter-section me-3">
            <ul class="nav nav-pills flex-column custom-nav">
                @foreach ($statuses as $filterStatus)
                    <li class="nav-item">
                        <a href="{{ route('getMyVacancy', ['status' => $filterStatus]) }}"
                           class="nav-link {{ $status === $filterStatus ? 'active' : '' }}">
                            {{ ucwords(str_replace('_', ' ', $filterStatus)) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Main Content (Kanan) -->
        <div class="content-section">
            <div class="row">
                @foreach ($userhrjobs as $vacancy)
                    <div class="col-lg-6 mb-4">
                        <div class="portfolio-item">
                             <a class="portfolio-link
                                {{ ($vacancy->status === 'applicant' && $vacancy->answers->isEmpty()) || $vacancy->status === 'hr_interview' ? '' : 'disabled' }}"
                                data-bs-toggle="modal"
                                href="{{ $vacancy->status === 'applicant' && $vacancy->answers->isEmpty() ? '#portfolioModal' . $vacancy->id : ($vacancy->status === 'hr_interview' ? '#hrinterviewModal' . $vacancy->id : '#') }}">

                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"></div>
                                </div>
                                <img class="img-fluid"
                                    src="{{ $vacancy->hrjob->job_image ? asset($vacancy->hrjob->job_image) : asset('storage/image/logopersegi.png') }}"
                                    alt="{{ $vacancy->hrjob->job_name }}" />
                            </a>
                            <div class="portfolio-caption">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="portfolio-caption-heading">{{ $vacancy->hrjob->job_name }}</div>
                                    </div>
                                    <div class="col-5 text-end">
                                        <div class="portfolio-caption-price">Rp {{ number_format($vacancy->hrjob->price, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="portfolio-caption-location">{{ $vacancy->hrjob->city->city_name }}</div>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="kaem-jobtext text-muted mb-0">{{ $vacancy->hrjob->category->category_name ?? 'No Category' }}</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="portfolio-caption-date">Sent On: {{ \Carbon\Carbon::parse($vacancy->created_at)->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <div class="portfolio-caption-price card border-light shadow-sm">{{ ucwords(str_replace('_', ' ', $vacancy->status)) }}</div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="portfolio-caption-date">Expired: {{ $vacancy->hrjob->expired }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-end">
                                        @if($vacancy->answers && $vacancy->answers->isEmpty())
                                            <span class="badge bg-danger">Form Not Filled</span>
                                        @else
                                            <span class="badge bg-success">Form Filled</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@foreach ($userhrjobs as $vacancy)
    @if ($vacancy->status === 'applicant' && isset($formsByJob[$vacancy->id_job]))
        <div class="modal fade" id="portfolioModal{{ $vacancy->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Answer Questions</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: white">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('storeMyAnswer') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_user_job" value="{{ $vacancy->id }}">
                            @foreach ($formsByJob[$vacancy->id_job] as $form)
                                <div class="form-group">
                                    <label class="kaem-subheading" for="answer_{{ $form->id }}">{{ $form->question->question }}</label>
                                    <div class="form-check">
                                        @foreach ([5 => 'Sangat Baik', 4 => 'Baik', 3 => 'Netral', 2 => 'Tidak Baik', 1 => 'Sangat Tidak Baik'] as $value => $label)
                                            <div class="form-check kaem-text">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    id="answer_{{ $form->id }}_{{ $value }}"
                                                    name="answers[{{ $loop->parent->index }}][answer]"
                                                    value="{{ $value }}"
                                                    required>
                                                <input type="hidden" name="answers[{{ $loop->parent->index }}][id_form]" value="{{ $form->id }}">
                                                <label class="form-check-label" for="answer_{{ $form->id }}_{{ $value }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary">Submit Answers</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($vacancy->status === 'hr_interview')
        <!-- Portfolio Modal for hrinterview -->
        <div class="modal fade" id="hrinterviewModal{{ $vacancy->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">HR Interview</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: white">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @foreach ($interviews as $interview)
                    <div class="modal-body">
                        <!-- Job details for hrinterview -->
                        {{-- <h2 class="kaem-jobtitle">{{ $interview->time }}</h2> --}}
                        <ul class="list-inline">
                            <li class="mb-2 kaem-jobtext">
                                <strong>Interviewer :</strong>
                                <p>{{ $interview->user->fullname }}</p>
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Applicant :</strong>
                                <p>{{ $interview->userhrjob->user->fullname }}</p>
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Date | Time :</strong>
                                <p>{{ $interview->interview_date }} | {{ $interview->time }} </p>
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Location :</strong>
                                <p>{{ $interview->location ?? 'Please Wait For Location' }}</p>
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Link :</strong>
                                <p>{{ $interview->link ?? 'Please Wait For Link'}}</p>
                            </li>
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection