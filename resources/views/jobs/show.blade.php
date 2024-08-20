<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ $job['title'] }}</h2>
        </div>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>{{ $job['title'] }}</h1>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Company</th>
                                    <td>{{ $job['company'] }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Designation</th>
                                    <td>{{ $job['designation']['name'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Description</th>
                                    <td>{!! $job['description'] !!}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Location</th>
                                    <td>{{ $job['location'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</x-layout>
