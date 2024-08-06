<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Jobs</h2>
            <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
        </div>
    </x-slot>

    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
<style>
    .collapse{
        visibility: visible;
    }
</style>
    <!-- Job Listings Table -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-4 flex-grow-1">Job Listings</h1>
            <div>
                <a href="{{ route('pdf.generatePDF') }}" class="ml-3 text-decoration-none" data-bs-toggle="tooltip"
                    data-bs-placement="PDF" title="PDF">
                    <i class="fa-solid fa-file-pdf" style="font-size: 40px;"></i>
                </a>
                <i class="fa-solid fa-file-import mr-3" data-toggle="modal" data-target="#importModal"
                    data-bs-toggle="tooltip" data-bs-placement="Import" title="Import"
                    style="font-size: 40px; color: #2C57B3; cursor: pointer;"></i>
                <a href="{{ route('jobs.export') }}">
                    <i class="fa-solid fa-file-excel text-decoration-none" data-bs-toggle="tooltip"
                        data-bs-placement="Excel" title="Excel" style="font-size: 40px;"></i>
                </a>
            </div>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-sm btn-primary " data-toggle="collapse" href="#collapseExample" role="button"
                aria-expanded="false" aria-controls="collapseExample">
                Filters
            </a>
        </div>
        <div class="collapse" id="collapseExample">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-row">
                        <!-- Filter Status -->
                        <div class="form-group col-md-2">
                            <label for="filter-status" class="sr-only">Status</label>
                            <select id="filter-status" class="form-control">
                                <option value="">--Select Status--</option>
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <!-- Filter Company -->
                        <div class="form-group col-md-2">
                            <label for="filter-company" class="sr-only">Company</label>
                            <select id="filter-company" class="form-control">
                                <option value="">--Select Company--</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company }}">{{ $company }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Location -->
                        <div class="form-group col-md-2">
                            <label for="filter-location" class="sr-only">Location</label>
                            <select id="filter-location" class="form-control">
                                <option value="">--Select Location--</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Designation -->
                        <div class="form-group col-md-2">
                            <label for="filter-designation" class="sr-only">Designation</label>
                            <select id="filter-designation" class="form-control">
                                <option value="">--Select Designation--</option>
                                @foreach ($designations as $designation)
                                    <option value="{{ $designation }}">{{ $designation }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Title -->
                        <div class="form-group col-md-2">
                            <label for="filter-title" class="sr-only">Title</label>
                            <select id="filter-title" class="form-control">
                                <option value="">--Select Title--</option>
                                @foreach ($titles as $title)
                                    <option value="{{ $title }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsivee">
            <table id="jobTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Designation</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Jobs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('jobs.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Choose CSV File</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Description Modal -->
    <div class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-labelledby="jobModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobModalLabel">Job Description</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="jobDescription"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <!-- SweetAlert JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <!-- Modal Script -->
    <script>
        function showDescription(description) {
            var plainText = description.replace(/<[^>]*>/g, '');
            document.getElementById('jobDescription').innerText = plainText;
            $('#jobModal').modal('show');
        }
    </script>

    <!-- DataTable Initialization Script -->
    <script>
        $(document).ready(function() {
            var table = $('#jobTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('jobs.index') }}",
                    data: function(d) {
                        d.title = $('#filter-title').val();
                        d.company = $('#filter-company').val();
                        d.designation = $('#filter-designation').val();
                        d.location = $('#filter-location').val();
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'company',
                        name: 'company'
                    },
                    {
                        data: 'designation',
                        name: 'designation.name'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        render: function(data) {
                            return '<a href="javascript:void(0)" onclick="showDescription(\'' + data
                                .replace(/'/g, "\\'") +
                                '\')" title="View Description"><i class="fa-solid fa-circle-info" style="color: #000000;"></i></a>';
                        }
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return '<form action="' + row.status_url +
                                '" method="POST" style="display: inline;">' +
                                '@csrf' +
                                '@method('PUT')' +
                                '<button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Toggle Status" class="btn btn-sm ' +
                                (data ? 'btn-success' : 'btn-danger') + '">' +
                                (data ? 'Enabled' : 'Disabled') + '</button>' +
                                '</form>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<a href="' + row.show_url +
                                '" title="View"><i class="fas fa-eye" style="color: #000000;"></i></a> ' +
                                '<a href="' + row.edit_url +
                                '" title="Edit"><i class="fas fa-edit" style="color: #000000; margin-left:3px;"></i></a> ' +
                                '<form action="' + row.delete_url +
                                '" method="POST" style="display: inline;">' +
                                '@csrf' +
                                '@method('DELETE')' +
                                '<i class="fas fa-trash show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" style="cursor: pointer;"></i>' +
                                '</form>';
                        }
                    }
                ],
                initComplete: function() {
                    $('#filter-title, #filter-company, #filter-designation, #filter-location, #filter-status')
                        .on('change keyup', function() {
                            table.draw();
                        });
                }
            });
        });
    </script>


    <!-- Delete Confirmation Script -->
    <script>
        $(document).on('click', '.show_confirm', function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            swal({
                    title: "Are you sure you want to delete this record?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>
</x-layout>
