<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Subjects</h2>
            <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
        </div>
    </x-slot>
    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="container">
        <strong class="mb-3">Subject List</strong> <button class="btn btn-danger btn-sm mt-2 mb-3 ml-2" id="bulkDeleteBtn" onclick="bulkDelete()">Bulk Delete</button>
        <div class="table-responsive">
            <table id="subjectTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="masterCheckbox"></th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="assignStudentsModal" tabindex="-1" role="dialog" aria-labelledby="assignStudentsModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignStudentsModalLabel">Assign Students to Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignStudentsForm">
                        <input type="hidden" id="subject_id" name="subject_id">
                        <div class="form-group">
                            <select id="students" class="form-control" placeholder="Select Students" multiple>

                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                    <button type="button" class="btn btn-primary" id="assignStudentsBtn" onclick="assignStudents()">Assign Students</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>

    <!-- DataTable Initialization Script -->
    <script>
        $(document).ready(function() {
            $('#subjectTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('subjects.index') }}",
                columns: [
                    { data: 'name', name: 'name', orderable: false, searchable: false, render: function(data, type, row) {
                        return '<input type="checkbox" class="rowCheckbox" value="' + row.id + '">';
                    }},

                    { data: 'name', name: 'name' },
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
                                   '</form>' +
                                   '<a href="javascript:void(0)" onclick="openAssignModal(' + row.id + ')" title="Assign Subjects"><i class="fa-solid fa-up-right-from-square" style="color: #000000; margin-left:3px;"></i></a>';
                        }
                    }
                ]
            });
            $('#masterCheckbox').on('click', function() {
                if ($(this).is(':checked')) {
                    $('.rowCheckbox').prop('checked', true);
                } else {
                    $('.rowCheckbox').prop('checked', false);
                }
            });
            $(document).on('click', '.rowCheckbox', function() {
                if ($('.rowCheckbox:checked').length == $('.rowCheckbox').length) {
                    $('#masterCheckbox').prop('checked', true);
                } else {
                    $('#masterCheckbox').prop('checked', false);
                }
            });
        });

        function bulkDelete() {
            var ids = [];
            $('.rowCheckbox:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length > 0) {
                swal({
                    title: "Are you sure you want to delete selected records?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('subjects.bulkDelete') }}',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    swal("Success", response.success, "success").then(() => {
                                        window.location.href = '{{ route('subjects.index') }}';
                                    });
                                } else if (response.warning) {
                                    swal("Warning", response.warning, "warning").then(() => {
                                        window.location.href = '{{ route('subjects.index') }}';
                                    });
                                }
                            }
                        });
                    }
                });
            } else {
                swal("Please select at least one record.");
            }
        }
    </script>

    <script>
        var student_ids_array = [];
        var students = @json($students);
        var selectElement = document.getElementById('students');
        var choicesInstance;

        function openAssignModal(subjectId) {
            $('#subject_id').val(subjectId);

            $.ajax({
                url: '{{ route("get.available.students", ":id") }}'.replace(':id', subjectId),
                type: 'GET',
                success: function(data) {
                    $('#students').empty();
                    student_ids_array = data;
                    students.forEach(student => {
                        if (!student_ids_array.includes(student.id)) {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.text = student.name;
                            selectElement.appendChild(option);
                        }
                    });


                    choicesInstance = new Choices('#students');
                    $('#assignStudentsModal').modal('show');
                }
            });
        }

        function closeModal() {
            if ($('#assignStudentsModal').is(':visible')) {
                if (choicesInstance) {
                    choicesInstance.destroy();
                }
                $('#assignStudentsModal').modal('hide');
            }
        }

        function assignStudents() {
            var subjectId = $('#subject_id').val();
            var students = $('#students').val();

            $.ajax({
                type: 'POST',
                url: '{{ route('assign.students') }}',
                data: {
                    subject_id: subjectId,
                    students: students,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route('subjects.index') }}';
                    }
                }
            });
        }
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

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
</x-layout>
