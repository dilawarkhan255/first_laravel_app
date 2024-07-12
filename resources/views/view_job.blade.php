@extends('Components.frontend.master')

@section('content')

@php
    $current = url()->current();
@endphp

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
        <a class="nav-link {{ Request::is('/view_job') ? 'active' : '' }}" href="/view_job">View Job</a>
    </div>
</nav>

<!-- Card Section -->
<section class="container mb-5" style="margin-top: 10rem !important;">
    <div class="row" id="job-cards">
        @foreach($jobs as $job)
            <div class="col-md-4 mb-4">
                <div class="card homecard" style="width: 18rem;">
                    <div class="card-body">
                        <div class="card-header bg-transparent border-bottom-0 text-center">
                            <p><a href="#!" class="text-dark fw-bold">{{ $job->title }}</a></p>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted">Company: {{ $job->company }}</p>
                            <p class="small text-muted">Location: {{ $job->location }}</p>
                            <p class="small text-muted">Posted: {{ \Carbon\Carbon::parse($job->created_at)->format('M d, Y') }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="/job-details/{{ $job->slug }}" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center">
        <button id="load-more" class="btn btn-primary">Load More</button>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let start = 6;
        const take = 6;
        const jobCards = document.getElementById('job-cards');
        const loadMoreButton = document.getElementById('load-more');

        function loadJobs(start, take) {
            fetch('{{ route('loadmorejobs') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ start: start })
            })
            .then(response => response.json())
            .then(data => {
                if (data.jobs.length < take) {
                    loadMoreButton.style.display = 'none';
                }
                data.jobs.forEach(job => {
                    const card = `
                        <div class="col-md-4 mb-4">
                            <div class="card homecard" style="width: 18rem;">
                                <div class="card-body">
                                    <div class="card-header bg-transparent border-bottom-0 text-center">
                                        <p><a href="#!" class="text-dark fw-bold">${job.title}</a></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="small text-muted">Company: ${job.company}</p>
                                        <p class="small text-muted">Location: ${job.location}</p>
                                        <p class="small text-muted">Posted: ${new Date(job.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="/job-details/${job.slug}" class="btn btn-sm btn-outline-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    jobCards.insertAdjacentHTML('beforeend', card);
                });
                start = data.next;
            });
        }

        loadMoreButton.addEventListener('click', function () {
            loadJobs(start, take);
        });
    });
</script>



@endsection
