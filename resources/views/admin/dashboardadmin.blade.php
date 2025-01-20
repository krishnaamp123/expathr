@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>

    <p class="mb-3">Information Page</p>

    <form method="GET" action="{{ route('getDashboardAdmin') }}">
        <div class="row mb-4">
            <div class="form-group mx-sm-2">
                <label for="start_date" class="sr-only">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date', $startDate) }}">
            </div>
            <span class="mx-2 mt-1">
                <i class="fas fa-arrow-right"></i>
            </span>
            <div class="form-group mx-sm-2">
                <label for="end_date" class="sr-only">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date', $endDate) }}">
            </div>
            <div class="form-group mx-sm-2">
                <button type="submit" class="btn btn-sm" style="background-color: #72A28A; color: white;">Filter</button>
                <a href="{{ route('getDashboardAdmin') }}" class="btn btn-sm" style="background-color: #8f8f8f; color: white;" onclick="document.getElementById('start_date').value='';document.getElementById('end_date').value='';">Clear Date</a>
                <a href="{{ route('getDashboardAdmin', ['all_time' => 1]) }}" class="btn btn-sm" style="background-color: #000; color: white;">View All Time</a>
            </div>
        </div>
    </form>

        <div class="row">

            <div class="col-xl-3 col-md-6">

                <div class="col-xl-12 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #72A28A;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #72A28A;">
                                        Total Applicants</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><p class="card-text">{{ $applicantCount }}</p></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #72A28A;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #72A28A;">
                                        Total Jobs</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><p class="card-text">{{ $jobCount }}</p></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-suitcase fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Candidate Source</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie pt-0 pb-0"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                <canvas id="sourceChart" width="447" height="306" style="display: block; height: 245px; width: 358px;" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-xl-6 col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Recruitment Funnel</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="funnelChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">

                <div class="col-xl-12 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #72A28A;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #72A28A;">
                                        Conversion Rate</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><p class="card-text">{{ number_format($hiringSuccessRate, 0) }}%</p></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #72A28A;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #72A28A;">
                                        Average Hiring Cost</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><p class="card-text">Rp {{ number_format($averageHiringCost, 0, ',', '.') }}</p></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tags fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #72A28A;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #72A28A;">
                                        Average Day To Hire</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><p class="card-text">{{ number_format($averageDayToHire, 1) }} Day</p></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #72A28A;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #72A28A;">
                                        Positions Timeframe Rate</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><p class="card-text">{{ number_format($percentageFilledOnTime, 0) }}%</p></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- <div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #72A28A;">Hired vs Rejected</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="hiredRejectedChart" width="977" height="600" style="display: block; height: 320px; width: 782px;" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Script untuk Source Chart
    const ctxSource = document.getElementById('sourceChart').getContext('2d');
    const sourceData = @json($percentageData);

    let platforms = Object.entries(sourceData.platforms);
    platforms.sort((a, b) => b[1] - a[1]);

    const maxVisible = 3;
    const visiblePlatforms = platforms.slice(0, maxVisible);
    const otherPlatformsCount = platforms.slice(maxVisible).reduce((sum, [, count]) => sum + count, 0);

    const labelsSource = visiblePlatforms.map(([platform]) =>
    platform
        .toLowerCase()
        .replace(/\b\w/g, char => char.toUpperCase())
).concat(['Other Platforms', 'Referals', 'Others']);
    const dataSource = visiblePlatforms.map(([, count]) => count)
        .concat([otherPlatformsCount, sourceData.referals, sourceData.others]);

    new Chart(ctxSource, {
        type: 'pie',
        data: {
            labels: labelsSource,
            datasets: [{
                data: dataSource,
                backgroundColor: [
                    '#0f4217', '#53915c', '#83e691',
                    '#72A28A', '#000', '#8f8f8f'
                ],
            }]
        },
    });

    // Script untuk Funnel Chart
    document.addEventListener('DOMContentLoaded', function () {
        const funnelData = @json($funnelData);

        // Status dan data sesuai urutan funnel
        const statuses = Object.keys(funnelData);
        const counts = Object.values(funnelData);

        const ctx = document.getElementById('funnelChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: statuses.map(status =>
                    status
                        .replace('_', ' ')
                        .toLowerCase()
                        .replace(/\b\w/g, char => char.toUpperCase())
                ),
                datasets: [{
                    label: 'Number of Users',
                    data: counts,
                    backgroundColor: [
                        'rgba(15, 66, 23, 0.5)',
                        'rgba(83, 145, 92, 0.5)',
                        'rgba(131, 230, 145, 0.5)',
                        'rgba(114, 162, 138, 0.5)',
                        'rgba(0, 0, 0, 0.5)',
                        'rgba(143, 143, 143, 0.5)',
                        'rgba(15, 66, 23, 0.5)',
                        'rgba(83, 145, 92, 0.5)',
                        'rgba(131, 230, 145, 0.5)',
                        'rgba(114, 162, 138, 0.5)'
                    ],
                    borderColor: [
                        'rgba(15, 66, 23, 1)',
                        'rgba(83, 145, 92, 1)',
                        'rgba(131, 230, 145, 1)',
                        'rgba(114, 162, 138, 1)',
                        'rgba(0, 0, 0, 1)',
                        'rgba(143, 143, 143, 1)',
                        'rgba(15, 66, 23, 1)',
                        'rgba(83, 145, 92, 1)',
                        'rgba(131, 230, 145, 1)',
                        'rgba(114, 162, 138, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // Orientasi horizontal
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan label dataset
                    }
                }
            }
        });
    });

</script>
@endsection
