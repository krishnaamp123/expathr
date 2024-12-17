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

        </div>
@endsection
