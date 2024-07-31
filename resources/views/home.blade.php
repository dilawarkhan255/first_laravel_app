@extends('Components.frontend.master')

@section('content')

@php
    $current = url()->current();
@endphp

<!-- Slider Section -->
<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carouselExampleControls" data-slide-to="0" class="active"></li>
      <li data-target="#carouselExampleControls" data-slide-to="1"></li>
      <li data-target="#carouselExampleControls" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{ asset('8.jpg') }}" alt="First slide">
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
    <section class="container mt-5 mb-5">
        <div class="row">
            @foreach ($jobs as $job)
                <div class="col-md-4 mb-4">
                    <div class="card homecard " style="width: 18rem;">
                        <div class="card-body">
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
                </div>
            @endforeach
        </div>


    </section>

@endsection






