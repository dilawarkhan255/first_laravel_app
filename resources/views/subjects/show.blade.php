<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ $subject->name }}</h2>
        </div>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Subject Details</h1>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</th>
                                    <td>{{ $subject->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Students</th>
                                    <td>
                                        @foreach ($subject->students as $student)
                                            <div class="form-check form-check-inline" id="student_{{ $student->id }}">
                                                <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}" checked onclick="unassignStudent(this)">
                                                <label class="form-check-label badge badge-primary">{{ $student->name }}</label>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

     <script>
         function unassignStudent(checkbox) {
             var subjectId = '{{ $subject->id }}';
             var studentId = $(checkbox).val();

             $.ajax({
                 type: 'POST',
                 url: '{{ route('unassign.students') }}',
                 data: {
                    subject_id: subjectId,
                    student_id : studentId,
                     _token: '{{ csrf_token() }}'
                 },
                 success: function(response) {
                     if (response.success) {
                         if (!$(checkbox).is(':checked')) {
                             $(checkbox).closest('.form-check-inline').remove();
                             alert('Student unassigned successfully.');
                         }
                     } else {
                         alert('Failed.');
                     }
                 }
             });
         }
     </script>

</x-layout>
