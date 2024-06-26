<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Job Designation</h2>
        </div>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Designations</h1>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                            <!-- <tr>
                                <th scope="row">ID</th>
                                <td>{{ $designation->id }}</td>
                            </tr> -->
                            <tr>
                                <th scope="row">Designation Name</th>
                                <td>{{ $designation->name }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div>
                            <a href="{{ route('designations.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</x-layout>
