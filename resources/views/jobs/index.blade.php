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

    <!-- Job Listings Table -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-4 flex-grow-1">Job Listings</h1>
            <div>
                <a href="{{ route('pdf.generatePDF') }}" class="ml-3 text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="PDF" title="PDF">
                    <i class="fa-solid fa-file-pdf" style="font-size: 40px;"></i>
                </a>
                <i class="fa-solid fa-file-import mr-3"  data-toggle="modal" data-target="#importModal" data-bs-toggle="tooltip" data-bs-placement="Import" title="Import" style="font-size: 40px; color: #2C57B3; cursor: pointer;"></i>
                <a href="{{ route('jobs.export') }}">
                    <i class="fa-solid fa-file-excel text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="Excel" title="Excel" style="font-size: 40px;"></i>
                </a>
            </div>
        </div>
        {{-- <div class="accordion" id="accordionExample">
            <div class="card">
              <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Collapsible Group Item #1
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                  Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Collapsible Group Item #2
                  </button>
                </h2>
              </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="card-body">
                  Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingThree">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Collapsible Group Item #3
                  </button>
                </h2>
              </div>
              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                <div class="card-body">
                  Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
              </div>
            </div>
        </div> --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <select id="filter-status" class="form-control">
                            <option value="">--Select Status--</option>
                            <option value="1">Enable</option>
                            <option value="0">Disable</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <input type="text" id="filter-company" class="form-control" placeholder="Company">
                    </div>

                    <div class="form-group col-md-3">
                        <input type="text" id="filter-location" class="form-control" placeholder="Location">
                    </div>

                    <div class="form-group col-md-3">
                        <input type="text" id="filter-designation" class="form-control" placeholder="Designation">
                    </div>

                    <div class="form-group col-md-3">
                        <input type="text" id="filter-title" class="form-control" placeholder="Title">
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
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
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
            var table = $('#jobTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('jobs.index') }}",
                    data: function(d) {
                        d.searchTerm = $('#jobTable_filter input').val();
                        d.title = $('#filter-title').val();
                        d.company = $('#filter-company').val();
                        d.designation = $('#filter-designation').val();
                        d.location = $('#filter-location').val();
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'company', name: 'company' },
                    { data: 'designation', name: 'designation.name' },
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
                                    '<button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Toggle Status" class="btn btn-sm ' + (data ? 'btn-success' : 'btn-danger') + '">' +
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
                            return '<a href="' + row.show_url + '" title="View"><i class="fas fa-eye" style="color: #000000;"></i></a> ' +
                                '<a href="' + row.edit_url + '" title="Edit"><i class="fas fa-edit" style="color: #000000; margin-left:3px;"></i></a> ' +
                                '<form action="' + row.delete_url + '" method="POST" style="display: inline;">' +
                                '@csrf' +
                                '@method("DELETE")' +
                                '<i class="fas fa-trash show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" style="cursor: pointer;"></i>' +
                                '</form>';
                        }
                    }
                ],
                initComplete: function () {
                    $('#jobTable_filter').addClass('form-group mb-3').find('input').addClass('form-control').attr('placeholder', 'Search jobs...');
                    $('#jobTable_filter input').on('keyup', function() {
                        table.search(this.value).draw();
                    });

                    $('#filter-title, #filter-company, #filter-designation, #filter-location, #filter-status').on('change keyup', function() {
                        table.draw();
                    });
                }
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
