<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Students</h2>
            <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
        </div>
    </x-slot>
    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="container">
        <h1>Subject List</h1>
        <div class="table-responsive">
            <table id="subjectTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="assignStudentsModal" tabindex="-1" role="dialog" aria-labelledby="assignStudentsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignStudentsModalLabel">Assign Students to Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="assignStudentsBtn"  onclick="assignStudents()">Assign Students</button>
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
                                   '<i class="fas fa-trash show_confirm" style="cursor: pointer;"></i>' +
                                   '</form>' +
                                   '<a href="javascript:void(0)" onclick="openAssignModal(' + row.id + ')" title="Assign Subjects"><i class="fa-solid fa-up-right-from-square" style="color: #000000; margin-left:3px;"></i></a>';
                        }
                    }
                ]
            });
        });
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
                student_ids_array = data.data;
                students.forEach(student => {
                    if (!student_ids_array.includes(student.id) && subjectId == data.subject_id) {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.text = student.name;
                        selectElement.appendChild(option);
                    }
                });

                if (choicesInstance) {
                    choicesInstance.destroy();
                }
                choicesInstance = new Choices('#students', {
                    removeItemButton: true
                });

                $('#assignStudentsModal').modal('show');
            }
        });
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
