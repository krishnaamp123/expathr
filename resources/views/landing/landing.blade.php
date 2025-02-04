@extends('landing.layout.app')
@section('title', 'Landing')
<link rel="icon" type="image/png" href="{{ asset('storage/image/logokotakkecil.png') }}">

@section('content')

        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-heading text-uppercase">Good Coffee All Around</div>
                <div class="masthead-subheading">Expat. Roasters</div>
                <a class="btn btn-primary btn-xl" href="#portfolio">FIND JOB</a>
            </div>
        </header>
        <!-- Portfolio Grid-->
        <section class="page-section" id="portfolio">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase-libre">Job Vacancy</h2>
                    <h3 class="section-subheading mb-5">Here are our latest job!</h3>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-4">
                        <label for="searchBar" class="kaem-subheading">Search by Position</label>
                        <div class="input-group-kaem">
                            <span class="input-icon-kaem">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="searchBar" class="form-control-kaem" placeholder="Search jobs...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="cityFilter" class="kaem-subheading">Search by City</label>
                        <select id="cityFilter" class="form-control select2">
                            <option value="">Select City</option>
                            @foreach ($landingjobs->pluck('city')->unique('id')->filter() as $city)
                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="categoryFilter" class="kaem-subheading">Search by Category</label>
                        <select id="categoryFilter" class="form-control select2">
                            <option value="">Select Category</option>
                            @foreach ($landingjobs->pluck('category')->unique('id')->filter() as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 mb-4">
                        <label for="button" class="kaem-subheading">&nbsp;</label>
                        <button type="button" class="btn btn-secondary btn-md" onclick="resetFilters()">&nbsp;Clear&nbsp;</button>
                    </div>
                </div>

                <div class="row">
                    @foreach ($landingjobs as $vacancy)
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
                                    <div class="portfolio-caption-location">{{ $vacancy->city->city_name  }}</div>
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
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-xl">SEE ALL JOBS</a>
                </div>
            </div>
        </section>
        <!-- About-->
        <section class="page-section" id="about">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase-libre">About</h2>
                    <h3 class="section-subheading mb-5">Our Story</h3>
                </div>
                <ul class="timeline">
                    <li>
                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="storage/image/expat4.png" style="width: 100%; height: 100%; object-fit: cover;" alt="..." /></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="subheading">Expat. Roasters</h4>
                            </div>
                            <div class="timeline-body"><p class="text-muted">Is a specialty coffee producer driven by desire to produce an exceptional, unpretentious brew, from the ground up.</p></div>
                        </div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="storage/image/expat2.png" style="width: 100%; height: 100%; object-fit: cover;" alt="..." /></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="subheading">As residents of "the island"</h4>
                            </div>
                            <div class="timeline-body"><p class="text-muted">Expat. Roasters works closely and respectfully with Balinese farmers and producers to source finest local product to compliment their nomadic collections of beans around the globe.</p></div>
                        </div>
                    </li>
                    <li>
                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="storage/image/expat3.png" style="width: 100%; height: 100%; object-fit: cover;" alt="..." /></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="subheading">Strive to foster</h4>
                            </div>
                            <div class="timeline-body"><p class="text-muted">The burgeoning coffee & barista community of Indonesia. Introducing the culture of making a good brew across the island, our mission is to give access to education and training to coffee professionals and coffee lovers!</p></div>
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
                    <h2 class="section-heading text-uppercase-libre">Store</h2>
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
@foreach ($landingjobs as $vacancy)
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
                <p class="kaem-jobtext text-muted mb-1">{{ $vacancy->category->category_name ?? 'No Category' }}</p>
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
@endforeach

<style>
    .portfolio-item {
        cursor: pointer; /* Mengubah kursor menjadi pointer saat berada di atas elemen portfolio-item */
    }
</style>

</html>
