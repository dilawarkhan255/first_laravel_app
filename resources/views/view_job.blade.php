@extends('Components.frontend.master')

@section('content')

@php
    $current = url()->current();
@endphp


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
                            <p class="small text-muted">Designation: {{ $job->designation->name }}</p>
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
    <div class="d-flex justify-content-center mt-4">
        <button id="load-more" class="btn btn-primary">Load More</button>
    </div>
</section>

<script>
    var search = "{{ isset($search_job) ? $search_job : '' }}";
    var all_loaded = false;
    var jobsCount = '{{ $totalJobs }}';
    const loadMoreButton = document.getElementById('load-more');
    if(jobsCount < 4){
        loadMoreButton.style.display = 'none';
    }
    document.addEventListener('DOMContentLoaded', function () {
        let start = 0;
        const take = 3;
        const jobCards = document.getElementById('job-cards');


        loadMoreButton.addEventListener('click', function () {
            start = parseInt(start) + parseInt(3);
            loadJobs(start, take);
        });
        function loadJobs(start, take) {
            fetch('{{ route('loadmorejobs') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ start: start,search:search })
            })
            .then(response => response.json())
            .then(data => {
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
                                          <p class="small text-muted">Designation: ${job.designation.name}</p>
                                        <p class="small text-muted">Location: ${job.location}</p>
                                        <p class="small text-muted">Posted: ${new Date(job.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="/job_details/${job.slug}" class="btn btn-sm btn-outline-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    jobCards.insertAdjacentHTML('beforeend', card);
                });

                if (data.all_loaded == true) {
                    loadMoreButton.style.display = 'none';
                }else{
                    loadMoreButton.style.display = 'block';
                }
            });
        }


    });
</script>
@endsection
