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

            <div class="col-xl-3 col-md-6 mb-4">
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

            <div class="col-xl-3 col-md-6 mb-4">
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

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #72A28A;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #72A28A;">
                                    Hiring Success Rate</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><p class="card-text">{{ number_format($hiringSuccessRate, 0) }}%</p></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
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

            <div class="col-xl-3 col-md-6 mb-4">
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

            <div class="card-body">
                <div class="chart-pie pt-0 pb-0"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="sourceChart" width="447" height="306" style="display: block; height: 245px; width: 358px;" class="chartjs-render-monitor"></canvas>
                </div>
            </div>

        </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('sourceChart').getContext('2d');
        const sourceData = @json($percentageData);

        // Gabungkan data platform dengan referals dan others
        let platforms = Object.entries(sourceData.platforms);

        // Urutkan platform berdasarkan jumlah (desc)
        platforms.sort((a, b) => b[1] - a[1]);

        // Ambil 3 platform teratas dan gabungkan sisanya menjadi "Other Platforms"
        const maxVisible = 3; // Maksimal platform yang ditampilkan
        const visiblePlatforms = platforms.slice(0, maxVisible);
        const otherPlatformsCount = platforms.slice(maxVisible).reduce((sum, [, count]) => sum + count, 0);

        // Siapkan data untuk chart
        const labels = visiblePlatforms.map(([platform]) => platform)
            .concat(['Other Platforms', 'Referals', 'Others']); // Menambahkan 'Other Platforms', 'Referals' dan 'Others'

        const data = visiblePlatforms.map(([, count]) => count)  // Data untuk 3 platform teratas
            .concat([otherPlatformsCount, sourceData.referals, sourceData.others]); // Tambahkan data 'Other Platforms', 'Referals', dan 'Others'

        const chartData = {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#0f4217', '#53915c', '#83e691', // Warna untuk 3 platform teratas
                    '#72A28A', // Warna untuk "Other Platforms"
                    '#000', // Warna untuk "Referals"
                    '#8f8f8f'  // Warna untuk "Others"
                ],
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: chartData,
        });
    </script>
@endsection
