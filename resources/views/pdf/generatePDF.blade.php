<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .enabled {
            color: green;
        }
        .disabled {
            color: red;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Date: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Company</th>
                <th>Designation</th>
                <th>Location</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->company }}</td>
                    <td>{{ $job->designation->name }}</td>
                    <td>{{ $job->location }}</td>
                    <td class="{{ $job->status ? 'enabled' : 'disabled' }}">
                        {{ $job->status ? 'Enabled' : 'Disabled' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
