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
            <h2 class="section-heading text-uppercase-libre">My Job</h2>
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
                        <div class="portfolio-item"
                                {{ ($vacancy->status === 'applicant' && $vacancy->userAnswer->isEmpty()) || $vacancy->status === 'hr_interview' || $vacancy->status === 'user_interview' ? '' : 'disabled' }}
                                data-bs-toggle="modal"
                                href="{{ $vacancy->status === 'applicant' && $vacancy->userAnswer->isEmpty() ? '#portfolioModal' . $vacancy->id : ($vacancy->status === 'hr_interview' ? '#hrinterviewModal' . $vacancy->id : ($vacancy->status === 'user_interview' ? '#userinterviewModal' . $vacancy->id : '#')) }}">
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading">{{ $vacancy->hrjob->job_name }}</div>
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
                                        @if (!isset($formsByJob[$vacancy->id_job]))
                                            <span class="badge bg-warning portfolio-caption-location">No Form</span>
                                        @elseif ($vacancy->userAnswer->isEmpty())
                                            <span class="badge bg-danger portfolio-caption-location">Form Not Filled</span>
                                        @else
                                            <span class="badge bg-success portfolio-caption-location">Form Filled</span>
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
                    <h5 class="modal-title">Form Questions for {{ $vacancy->hrjob->job_name }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: white">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('storeMyAnswer') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_user_job" value="{{ $vacancy->id }}">

                        @foreach ($formsByJob[$vacancy->id_job] as $formHrjob)
                            <h6 class="modal-subtitle">{{ $formHrjob->form->form_name }}</h6>

                            @foreach ($formHrjob->form->questions as $question)
                                <div class="form-group">
                                    <label class="modal-subtitlewhite" for="question_{{ $question->id }}">{{ $question->question_name }}</label>
                                    <div class="form-check">
                                        @foreach ($question->answers as $answer)
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    id="answer_{{ $question->id }}_{{ $answer->id }}"
                                                    name="answers[{{ $question->id }}]"
                                                    value="{{ $answer->id }}"
                                                    required>
                                                <label class="form-check-label modal-subtitlewhite" for="answer_{{ $question->id }}_{{ $answer->id }}">
                                                    {{ $answer->answer_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endforeach

                        <button type="submit" class="btn btn-primary kaem-subheading">Submit Answers</button>
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
                    @foreach ($vacancy->interviews as $interview)
                    <div class="modal-body">
                        <ul class="list-inline">
                            <li class="mb-2 kaem-jobtext">
                                <strong>Interviewer :</strong>
                                @if ($interview->interviewers->isNotEmpty())
                                    <ul>
                                        @foreach ($interview->interviewers as $interviewer)
                                            <li class="mb-2 kaem-jobtext">{{ $interviewer->fullname }}</li>
                                        @endforeach
                                </ul>
                                @else
                                    <p>Please Wait For Interviewer</p>
                                @endif
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Applicant :</strong>
                                <p>{{ $interview->userhrjob->user->fullname }}</p>
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Date | Time :</strong>
                                <p>{{ $interview->interview_date?? 'Please Wait For Date' }} | {{ $interview->time ?? 'Please Wait For Time' }} </p>
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
                        <form action="{{ route('confirmArrival', ['interview' => $interview->id]) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')
                            @if ($interview->arrival === 'yes')
                                <button type="button" class="btn btn-primary kaem-subheading" disabled>Confirmed</button>
                            @else
                                <button type="submit" class="btn btn-primary kaem-subheading">Confirm Attendance</button>
                            @endif
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @elseif ($vacancy->status === 'user_interview')
        <!-- Portfolio Modal for hrinterview -->
        <div class="modal fade" id="userinterviewModal{{ $vacancy->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">User Interview</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: white">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @foreach ($vacancy->userinterviews as $userinterview)
                    <div class="modal-body">
                        <ul class="list-inline">
                            <li class="mb-2 kaem-jobtext">
                                <strong>Interviewer :</strong>
                                @if ($userinterview->user_interviewers->isNotEmpty())
                                    <ul>
                                        @foreach ($userinterview->user_interviewers as $interviewer)
                                            <li class="mb-2 kaem-jobtext">{{ $interviewer->fullname }}</li>
                                        @endforeach
                                </ul>
                                @else
                                    <p>Please Wait For Interviewer</p>
                                @endif
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Applicant :</strong>
                                <p>{{ $userinterview->userhrjob->user->fullname }}</p>
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Date | Time :</strong>
                                <p>{{ $userinterview->interview_date?? 'Please Wait For Date' }} | {{ $userinterview->time ?? 'Please Wait For Time' }} </p>
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Location :</strong>
                                <p>{{ $userinterview->location ?? 'Please Wait For Location' }}</p>
                            </li>
                            <li class="mb-2 kaem-jobtext">
                                <strong>Link :</strong>
                                <p>{{ $userinterview->link ?? 'Please Wait For Link'}}</p>
                            </li>
                        </ul>
                        <form action="{{ route('confirmUserArrival', ['userinterview' => $userinterview->id]) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')
                            @if ($userinterview->arrival === 'yes')
                                <button type="button" class="btn btn-primary kaem-subheading" disabled>Confirmed</button>
                            @else
                                <button type="submit" class="btn btn-primary kaem-subheading">Confirm Attendance</button>
                            @endif
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach

<style>
    .portfolio-item {
        cursor: pointer; /* Mengubah kursor menjadi pointer saat berada di atas elemen portfolio-item */
    }
</style>

@endsection
