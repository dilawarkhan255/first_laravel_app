@extends('Components.frontend.master')

@section('content')
    <section class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>{{ $job->title }}</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Company: {{ $job->company }}</h5>
                        <p class="card-text"><strong>Location:</strong> {{ $job->location }}</p>
                        <p class="card-text"><strong>Posted:</strong> {{ \Carbon\Carbon::parse($job->created_at)->format('M d, Y') }}</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('home') }}" class="btn btn-secondary">Back to Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
