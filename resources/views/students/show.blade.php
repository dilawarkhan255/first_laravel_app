<x-layout>
    <x-slot name="heading">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ $student->name }}</h2>
        </div>
    </x-slot>

    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Student Details</h1>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</th>
                                    <td>{{ $student->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $student->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Phone</th>
                                    <td>{{ $student->phone }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>{{ $student->address }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Subjects</th>
                                    <td>
                                        @foreach ($student->subjects as $subject)
                                            <div class="form-check form-check-inline" id="subject_{{ $subject->id }}">
                                                <input class="form-check-input" type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" checked onclick="unassignSubject(this)">
                                                <label class="form-check-label badge badge-primary">{{ $subject->name }}</label>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        function unassignSubject(checkbox) {
            var studentId = '{{ $student->id }}';
            var subjectId = $(checkbox).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('unassign.subjects') }}',
                data: {
                    student_id: studentId,
                    subject_id: subjectId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        if (!$(checkbox).is(':checked')) {
                            $(checkbox).closest('.form-check-inline').remove();
                            alert('Subject unassigned successfully.');
                        }
                    } else {
                        alert('Failed to update subject assignment.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error occurred. Please try again.');
                }
            });
        }
    </script>
</x-layout>
