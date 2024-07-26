<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h5>
        </div>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ isset($role) ? 'Edit Role' : 'Create New Role' }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST">
                            @csrf
                            @if(isset($role))
                                @method('PUT')
                            @endif
                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter Role" value="{{ old('name', isset($role) ? $role->name : '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="permissions">Permissions</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="select-all" onclick="toggleCheckboxes(this)">
                                    <label class="form-check-label" for="select-all">Select All</label>
                                </div>
                                <div>
                                    <div class="form-check ">
                                        <input type="checkbox" class="form-check-input " id="all" name="permission[]" value="all">
                                    </div>
                                    @foreach ($permissions as $permission)
                                        <div class="form-check ">
                                            <input type="checkbox" class="form-check-input" id="permission{{ $permission->id }}" name="permission[]" value="{{ $permission->id }}" {{ isset($rolePermissions) && in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label badge badge-primary" for="permission{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ isset($role) ? 'Update' : 'Create' }}</button>
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary ml-2">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function toggleCheckboxes(source) {
            const checkboxes = document.querySelectorAll('input[name="permission[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>
</x-layout>
