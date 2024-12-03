@extends('admin.layout.app')
@section('title', 'Answer')
@section('content')

    <!-- Definisikan Pemetaan Jawaban -->
    @php
    $answerLabels = [
        1 => 'Sangat Tidak Baik',
        2 => 'Tidak Baik',
        3 => 'Netral',
        4 => 'Baik',
        5 => 'Sangat Baik',
    ];
    @endphp

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Answer</h1>

    @if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('message')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <p class="mb-3">To see the applicant's answer</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        {{-- <div class="card-header py-3">
            <a href="{{route('addAnswer')}}" class="btn btn-sm" style="background-color: #72A28A; color: white;"><i class="fas fa-plus"></i> Add </a>
        </div> --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="fa-sm text-center">
                            <th>ID</th>
                            <th>Job Name</th>
                            <th>Applicant</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($answers as $row)
                        <tr class="fa-sm">
                            <td>{{$row->id}}</td>
                            <td>{{$row->userhrjob->hrjob->job_name ?? 'No Job'}}</td>
                            <td>{{$row->userhrjob->user->fullname ?? 'No Applicant'}}</td>
                            <td>{{$row->form->question->question ?? 'No Question'}}</td>
                            <!-- Ganti Jawaban Numerik dengan Label Teks -->
                            <td>{{ $answerLabels[$row->answer] ?? 'Unknown' }}</td>
                            <td>{{$row->created_at}}</td>
                            <td>{{$row->updated_at}}</td>
                            <td>
                                {{-- <a href="{{ route('editAnswer', $row->id) }}" class="btn btn-sm my-1" style="background-color: #969696; color: white;"><i class="fas fa-edit"></i> Edit</a> --}}
                                <form action="{{ route('destroyAnswer', $row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: #c03535; color: white;" onclick="return confirm('Are you sure you want to delete this answer?')"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
