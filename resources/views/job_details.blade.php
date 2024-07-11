@extends('Components.frontend.master')

@section('content')

<div class="container">

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container">
             <!-- Logo -->
             <a class="navbar-brand ms-auto" href="#"><img src="{{ asset('vec.png') }}" alt="." style="height: 50px"></a>

            <!-- Search Bar -->
            <form class="d-flex mx-auto mt-3">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

             <!-- Home Link -->
             <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">Home</a>
        </div>
    </nav>

    <!-- Button Section -->
    <div class="text-left mb-3" style="margin-top: 100px;">
        <a href="{{ route('home') }}"><i class="fas fa-angle-left"> <strong>Back</strong> </a></i>
    </div>

    <!-- Cover Image with Text -->
    <div class="p-0 position-relative">
        <div class="row no-gutters">
            <div class="col-12 position-relative">
                <img src="{{ asset('cover.jpg') }}" alt="Cover Image" class="img-fluid w-100 banner-image">
                <div class="overlay d-flex justify-content-center align-items-center text-white text-center">
                    <div>
                        <h1>Welcome to our Job Portal</h1>
                        <p class="lead">Find your dream job today!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3>{{ $job->title }}</h3>
                <div>
                    <button class="btn btn-dark">Apply</button>
                    <span class="mx-2"><i class="far fa-heart"></i></span>
                    <span><i class="fas fa-share-alt-square"></i></span>
                </div>
            </div>
            <div class="text d-flex justify-content-between mt-3">
                <div>
                    <span class="card-title">{{ $job->company }}</span>
                    <span class="card-text"><i class="fas fa-map-marker-alt"></i> {{ $job->location }}, Pakistan</span>
                </div>
                <div class="text-right">
                    <p class="card-text"><i class="fas fa-map-marker-alt"></i> Posted:{{ \Carbon\Carbon::parse($job->created_at)->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="btn btn-sm btn-light mt-3">
                <span>Full-Time</span>
            </div>
            <hr class="seperator" style="margin-top: 30px">
            <div class="row">
                <div class="col-md-6">
                    <div class="summery">
                        <h3>Job Summary</h3>
                        <p>I have work as a beginner level for 2 years.</p>
                    </div>
                    <hr class="seperator2 w-100" style="margin-top: 30px;">
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <i class="fas fa-info-circle fa-3x"></i>
                        <p>Additional Information</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
