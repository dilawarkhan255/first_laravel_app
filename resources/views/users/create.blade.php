<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Create User</h2>
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

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>Add New User</div>
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="post">
                        @csrf

                        <!-- Name Input -->
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end mb-1">Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-end mb-1">Email Address</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3 row">
                            <label for="password" class="col-md-4 col-form-label text-md-end mb-1">Password</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Confirmation Input -->
                        <div class="mb-3 row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-end mb-1">Confirm Password</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <!-- Role Select -->
                        <div class="mb-3 row">
                            <label for="role_id" class="col-md-4 col-form-label text-md-end mb-1">Role</label>
                            <div class="col-md-8">
                                <select class="form-control" name="role_id" id="role">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Permissions Section -->
                        <div class="mb-3 row">
                            <label for="permissions" class="col-md-4 col-form-label text-md-end mb-1">Permissions</label>
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="select-all" onclick="toggleCheckboxes(this)">
                                        <label class="form-check-label" for="select-all">Select All</label>
                                    </div>
                                </div>

                                <!-- Permissions Grid -->
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input permission-checkbox" id="permission_{{ $permission->id }}" name="permission[]" value="{{ $permission->id }}">
                                                <label class="form-check-label ms-2 text-truncate" for="permission_{{ $permission->id }}">
                                                    <span class="badge badge-primary">{{ $permission->name }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-8 offset-md-4">
                                <input type="submit" class="btn btn-primary" value="Add User">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function toggleCheckboxes(selectAllCheckbox) {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        // To update the Select All checkbox based on individual checkboxes
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const allChecked = Array.from(permissionCheckboxes).every(checkbox => checkbox.checked);
                document.getElementById('select-all').checked = allChecked;
            });
        });
    </script>

</x-layout>
