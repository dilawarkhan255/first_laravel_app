<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ $user->name }}</h2>
        </div>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        User Information
                    </div>
                    <div class="float-end">
                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                    </div>
                </div>
                <div class="card-body">

                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                                {{ $user->name }}
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-end text-start"><strong>Email Address:</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                                {{ $user->email }}
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Roles:</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                            @php
                                $roles = $user->roles ?? collect();
                            @endphp
                            @forelse ($roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @empty
                                <span>No roles assigned</span>
                            @endforelse

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</x-layout>
