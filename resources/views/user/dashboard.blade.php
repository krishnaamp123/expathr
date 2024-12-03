@extends('user.layout.app')
@section('title', 'Dashboard')

@section('content')

        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-subheading">Expat. Roasters</div>
                <div class="masthead-heading text-uppercase">Good Coffe All Around</div>
                <a class="btn btn-primary btn-xl text-uppercase" href="#portfolio">Find Job</a>
            </div>
        </header>
        <!-- Portfolio Grid-->
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
                    <h3 class="section-subheading">Here are our latest job!</h3>
                    <h3 class="section-subheading-kaem mb-5">Make sure you have completed your profile first!</h3>
                </div>
                <div class="row">
                    @foreach ($davacancies as $vacancy)
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
                                    <div class="portfolio-caption-location">{{ $vacancy->city->city_name  }}</div>
                                    <div class="divider"></div>
                                    <div class="portfolio-caption-date">Expired: {{ $vacancy->expired }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('getVacancy') }}" class="btn btn-primary btn-xl text-uppercase">See All Jobs</a>
                </div>
            </div>
        </section>
        <!-- About-->
        <section class="page-section" id="about">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">About</h2>
                    <h3 class="section-subheading mb-5 text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
                <ul class="timeline">
                    <li>
                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/1.jpg" alt="..." /></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>2009-2011</h4>
                                <h4 class="subheading">Our Humble Beginnings</h4>
                            </div>
                            <div class="timeline-body"><p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p></div>
                        </div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/2.jpg" alt="..." /></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>March 2011</h4>
                                <h4 class="subheading">An Agency is Born</h4>
                            </div>
                            <div class="timeline-body"><p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p></div>
                        </div>
                    </li>
                    <li>
                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/3.jpg" alt="..." /></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>December 2015</h4>
                                <h4 class="subheading">Transition to Full Service</h4>
                            </div>
                            <div class="timeline-body"><p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p></div>
                        </div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/4.jpg" alt="..." /></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>July 2020</h4>
                                <h4 class="subheading">Phase Two Expansion</h4>
                            </div>
                            <div class="timeline-body"><p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p></div>
                        </div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image">
                            <h4>
                                Be Part
                                <br />
                                Of Our
                                <br />
                                Story!
                            </h4>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <!-- Store -->
        <section class="page-section" id="store">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Store</h2>
                    <h3 class="section-subheading mb-5">Here is our location!</h3>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-6 mb-4">
                        <!-- Contact item 1-->
                        <div class="store-item">
                            <img class="img-fluid" src="{{ asset('template-user/assets/img/store/store1.png') }}" alt="..." />
                            <div class="store-caption">
                                <div class="store-caption-heading">Expat. Roasters Brew Bar</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Address</div>
                                <div class="store-caption-text">Petitenget St No.1A, Kerobokan Kelod, North Kuta Badung Regency, Bali 80361, Indonesia</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Operating Hours</div>
                                <div class="store-caption-text">Monday to Sunday, 7am-7pm</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Phone</div>
                                <div class="store-caption-text">+62 812 4614 0493</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-4">
                        <!-- Contact item 1-->
                        <div class="store-item">
                            <img class="img-fluid" src="{{ asset('template-user/assets/img/store/store2.png') }}" alt="..." />
                            <div class="store-caption">
                                <div class="store-caption-heading">Expat. Roasters Beachwalk</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Address</div>
                                <div class="store-caption-text">Beachwalk Shopping Mall Level 3, Jl. Pantai Kuta No.1, Kuta, Kabupaten Badung, Bali 80361, Indonesia</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Operating Hours</div>
                                <div class="store-caption-text">Monday to Sunday, 11am-10pm</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Phone</div>
                                <div class="store-caption-text">+62 812 3889 8406</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-4">
                        <!-- Contact item 1-->
                        <div class="store-item">
                            <img class="img-fluid" src="{{ asset('template-user/assets/img/store/store3.png') }}" alt="..." />
                            <div class="store-caption">
                                <div class="store-caption-heading">Expat. Roasters Surabaya</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Address</div>
                                <div class="store-caption-text">Soho Graha Famili PS. 15, Jl. Raya Graha Famili Timur, Surabaya, Jawa Timur 60225</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Operating Hours</div>
                                <div class="store-caption-text">Monday to Sunday, 6am-9pm</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Phone</div>
                                <div class="store-caption-text">+62 821 3161 8995</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-4">
                        <!-- Contact item 1-->
                        <div class="store-item">
                            <img class="img-fluid" src="{{ asset('template-user/assets/img/store/store4.png') }}" alt="..." />
                            <div class="store-caption">
                                <div class="store-caption-heading">Expat. Roasters Jakarta</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Address</div>
                                <div class="store-caption-text">Jakarta Mori Tower, 13th Floor, Jl. Jenderal Sudirman Jakarta Pusat, Jakarta 10210</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Operating Hours</div>
                                <div class="store-caption-text">Monday to Sunday, 7am-7pm</div>
                                <div class="sizedbox"></div>
                                <div class="store-caption-subheading">Phone</div>
                                <div class="store-caption-text">+62 811 3830 6012</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<!-- Portfolio Modals -->
@foreach ($davacancies as $vacancy)
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
                <button class="btn btn-primary kaem-subheading mb-3"
                    @if (auth()->user() && auth()->user()->hasAppliedFor($vacancy->id))
                        disabled
                    @else
                        data-bs-toggle="modal" data-bs-target="#applyModal{{ $vacancy->id }}"
                    @endif>
                    @if (auth()->user() && auth()->user()->hasAppliedFor($vacancy->id))
                        Already Applied
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
                        <span class="kaem-jobtext ms-2">{{ ucwords(str_replace('_', ' ', $vacancy->location_type)) }} - {{ $vacancy->city->city_name }}</span>
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
</html>
