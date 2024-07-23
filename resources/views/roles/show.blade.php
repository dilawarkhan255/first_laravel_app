<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ $role->name }}</h2>
        </div>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Role Details
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
                    </div>
                    <div class="card-body">
                        <h3>Role Name: {{ $role->name }}</h3>

                        <h4 class="mt-4">Permissions:</h4>
                        <ul>
                            @forelse ($role->permissions as $permission)
                                <li>{{ $permission->name }}</li>
                            @empty
                                <li>No permissions assigned</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</x-layout>
