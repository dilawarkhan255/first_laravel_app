<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Designations</h2>
            <a href="{{ route('designations.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
        </div>
    </x-slot>

    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="container">
        <div class="card" style="background-color: #F7F7F9; border-color: #f2f2f2;">
            <div class="card-body">
                <h1 class="mt-1">Job Designations</h1>

                <div class="table-responsive">
                    <table id="designationTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Name</th>
                                <th style="width: 30%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobDesignations as $designation)
                                <tr>
                                    <td>{{ $designation->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('designations.show', ['jobDesignation' => $designation]) }}" title="View">
                                                <i class="fa-solid fa-eye" style="color: #000;"></i>
                                            </a>
                                            <a href="{{ route('designations.edit', ['jobDesignation' => $designation]) }}" title="Edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #000;"></i>
                                            </a>
                                            <form action="{{ route('designations.destroy', ['jobDesignation' => $designation]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0" title="Delete"><i class="fa-solid fa-trash" style="color: #000;"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#designationTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });
    </script>
</x-layout>
