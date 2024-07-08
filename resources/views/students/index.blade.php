<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Students</h2>
            <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
        </div>
    </x-slot>

    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="container">
        <h1>Students List</h1>
        <button class="btn btn-danger btn-sm mt-2 mb-2" id="bulkDeleteBtn" onclick="bulkDelete()">Delete Selected</button>
        <div class="table-responsive">
            <table id="studentTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="masterCheckbox"></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Assign Subjects Modal -->
    <div class="modal fade" id="assignSubjectsModal" tabindex="-1" role="dialog" aria-labelledby="assignSubjectsModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignSubjectsModalLabel">Assign Subjects to Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignSubjectsForm">
                        <input type="hidden" id="student_id" name="student_id">
                        <div class="form-group">
                            <select id="subjects" class="form-select" placeholder="Select Subjects" multiple></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal()">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="assignSubjectsBtn" onclick="assignSubjects()">Assign Subjects</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#studentTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('students.index') }}",
                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable: false, render: function(data, type, row) {
                        return '<input type="checkbox" class="rowCheckbox" value="' + row.id + '">';
                    }},
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address' },
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
                                '<i class="fas fa-trash show_confirm" style="cursor: pointer;"></i>' +
                                '</form> ' +
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
                            url: '{{ route('students.bulkDelete') }}',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    window.location.href = '{{ route('students.index') }}';
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
        var subject_ids_array = [];
        var subjects = @json($subjects);
        var selectElement = document.getElementById('subjects');
        var choicesInstance;

        function openAssignModal(studentId) {
            $('#student_id').val(studentId);

            $.ajax({
                url: '{{ route("get.available.subjects", ":id") }}'.replace(':id', studentId),
                type: 'GET',
                success: function(data) {
                    $('#subjects').empty();

                    subjects.forEach(subject => {
                        console.log(!data.includes(subject.id));
                        if (!data.includes(subject.id)) {
                            const option = $('<option>', {
                                value: subject.id,
                                text: subject.name
                            });
                            $('#subjects').append(option);
                        }
                    });



                    choicesInstance = new Choices('#subjects');
                    $('#assignSubjectsModal').modal('show');
                }
            });
        }

        function closeModal() {
            if ($('#assignSubjectsModal').is(':visible')) {
                if (choicesInstance) {
                    choicesInstance.destroy();
                }
                $('#assignSubjectsModal').modal('hide');
            }
        }

        function assignSubjects() {
            var studentId = $('#student_id').val();
            var subjects = $('#subjects').val();

            $.ajax({
                type: 'POST',
                url: '{{ route('assign.subjects') }}',
                data: {
                    student_id: studentId,
                    subjects: subjects,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route('students.index') }}';
                    }
                }
            });
        }
    </script>
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
