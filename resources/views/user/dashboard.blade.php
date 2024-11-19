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
                    <h2 class="section-heading text-uppercase">Job Vacancy</h2>
                    <h3 class="section-subheading">Here are our latest job vacancies!</h3>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-6 mb-4">
                        <!-- Portfolio item 1-->
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal1">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"></div>
                                </div>
                                <img class="img-fluid" src="{{ asset('template-user/assets/img/store/store1.png') }}" alt="..." />
                            </a>
                            <div class="portfolio-caption">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="portfolio-caption-heading">Mobile Developer</div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="portfolio-caption-price">Rp 3,5 jt</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="portfolio-caption-type">Full-Time</div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="portfolio-caption-type">Remote</div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="portfolio-caption-type">Minimum S1</div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="portfolio-caption-type">1-3 Year</div>
                                    </div>
                                </div>
                                <div class="portfolio-caption-location">Expat. Roasters Head Office and Training Academy</div>
                                <div class="divider"></div>
                                <div class="portfolio-caption-date">5 hari yang lalu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About-->
        <section class="page-section" id="about">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">About</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
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
                    <h3 class="section-subheading">Here is our location!</h3>
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
        <!-- Portfolio Modals-->
        <!-- Portfolio item 1 modal popup-->
        <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><img src="{{ asset('template-user/assets/img/close-icon.svg') }}" alt="Close modal" /></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <!-- Project details-->
                                    <h2 class="text-uppercase">Project Name</h2>
                                    <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                                    <img class="img-fluid d-block mx-auto" src="assets/img/portfolio/1.jpg" alt="..." />
                                    <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                                    <ul class="list-inline">
                                        <li>
                                            <strong>Client:</strong>
                                            Threads
                                        </li>
                                        <li>
                                            <strong>Category:</strong>
                                            Illustration
                                        </li>
                                    </ul>
                                    <button class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-xmark me-1"></i>
                                        Close Project
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</html>
