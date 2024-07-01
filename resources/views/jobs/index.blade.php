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
    <div class="container">
        <h1>Job Listings</h1>
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

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-labelledby="jobModalLabel" aria-hidden="true">
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
            $('#jobTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('jobs.index') }}",
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'company', name: 'company' },
                    { data: 'designation', name: 'designation' },
                    { 
                        data: 'description', 
                        name: 'description',
                        render: function(data) {
                            return '<a href="javascript:void(0)" onclick="showDescription(\'' + data.replace(/'/g, "\\'") + '\')" title="View Description"><i class="fa-solid fa-circle-info" style="color: #000000;"></i></a>';
                        }
                    },
                    { data: 'location', name: 'location' },
                   
                    { 
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return '<form action="' + row.status_url + '" method="POST" style="display: inline;">' +
                                    '@csrf' +
                                    '@method("PUT")' +
                                    '<button type="submit" class="btn btn-sm ' + (data ? 'btn-danger' : 'btn-success') + '">' + 
                                    (data ? 'Disable' : 'Enable') + '</button>' +
                                '</form>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<a href="' + row.show_url + '" title="View"><i class="fas fa-eye" style="color: #000000;"></i></a> ' +
                                '<a href="' + row.edit_url + '" title="Edit"><i class="fas fa-edit" style="color: #000000; margin-left:3px;"></i></a> ' +
                                '<form action="' + row.delete_url + '" method="POST" style="display: inline;">' +
                                '@csrf' +
                                '@method("DELETE")' +
                                '<button type="submit" style="border: none; background-color: transparent; color: #000000; margin-left: 5px;"><i class="fas fa-trash"></i></button>' +
                                '</form>';
                        }
                    }

                ]
            });
        });
    </script>

    <!-- Delete Confirmation Script -->
    <script>
        $(document).on('click', '.show_confirm', function(event) {
            var form =  $(this).closest("form");
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
