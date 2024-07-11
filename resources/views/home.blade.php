@extends('Components.frontend.master')

@section('content')

@php
    $current = url()->current();
@endphp
<nav class="navbar navbar-expand-lg navbar-landing navbar-light fixed-top {{ $current != url('/') ? 'header_back' : '' }}"
    style="opacity: 0.97;" id="navbar">


    <div class="container">
        <a class="navbar-brand" href="/">
            <h3 class="card-logo card-logo-light text-white {{ $current != url('/') ? 'header_class' : '' }}">
                <span id="w_word">J</span>OBSPORTAL
            </h3>
            {{-- <img src="{{URL::asset('assets/images/logo-dark.png')}}" class="card-logo card-logo-dark" alt="logo dark" height="17">
        <img src="{{URL::asset('assets/images/logo-light.png')}}" class="card-logo card-logo-light" alt="logo light" height="17"> --}}
        </a>
        <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="mdi mdi-menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                <li class="nav-item">
                    <a class="nav-link {{ $current == url('/') ? 'active' : ''  }} {{ $current != url('/') ? 'header_class' : '' }}"
                        href="/" style="color: #2f6293;">Home</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link rounded-md {{ Request::is('login') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-500' }}" href="/login" style="text-decoration:none; color: #f5f5f5;">Log In</a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link rounded-md {{ Request::is('register') ? 'bg-gray-900 text-white' : 'text-gray-300 ' }}" href="/register" style="text-decoration:none;  color: #f5f5f5;">Register</a>
                </li> --}}
            </ul>
        </div>

    </div>
</nav>
<!-- Slider Section -->
<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carouselExampleControls" data-slide-to="0" class="active"></li>
      <li data-target="#carouselExampleControls" data-slide-to="1"></li>
      <li data-target="#carouselExampleControls" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{ asset('image1.jpg') }}" alt="First slide">
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="{{ asset('image2.jpg') }}" alt="Second slide">
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="{{ asset('image3.jpg') }}" alt="Third slide">
      </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>


    <!-- Card Section -->
    <section class="container mt-5">
        <div class="row">
            @foreach ($jobs as $job)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm homecard" style="border-radius: 15px;">
                    <div class="card-header bg-transparent border-bottom-0 text-center">
                        <p><a href="#!" class="text-dark fw-bold ">{{ $job->title }}</a></p>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Company: {{ $job->company }}</p>
                        <p class="small text-muted">Location: {{ $job->location }}</p>
                        <p class="small text-muted">Posted: {{ \Carbon\Carbon::parse($job->created_at)->format('M d, Y') }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('job_details', ['slug' => $job->slug]) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

@endsection






