<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <!-- Theme style -->
    <link rel="stylesheet" href="">
    <style>
        body {
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-size: .7rem;
        }

        #table tr:nth-child(odd) {
            background-color: #F2F2F2;
        }

        #table tr:nth-child(even) {
            background-color: #ffffff;
        }

        .bg-danger {
            color: white;
        }

        #table td {
            padding: .5rem;
            vertical-align: top;
        }

        #table th {
            padding: .5rem;
            border: 1px solid #dee2e6;
            vertical-align: bottom;
            border-bottom-width: 2px;
        }

        #search-info tr:nth-child(odd) {
            background-color: #F2F2F2;
        }

        #search-info tr:nth-child(even) {
            background-color: #F2F2F2;
        }

        .bg-danger {
            color: white;
        }

        #search-info td {
            padding: .5rem;
            vertical-align: top;
        }

        #search-info th {
            padding: .5rem;
            border: 1px solid #dee2e6;
            vertical-align: bottom;
            background-color: #ffffff;
        }
    </style>
</head>
<body>

<div style="width: 100%; background-color: white;text-align: center;">
    <img src="https://www.khaborerkagoj.com/public/frontend/common/images/logo.png" alt="khaborer kagoj" height="30"
         width="140">
</div>

<div style="width: 100%; background-color: white;text-align: center;">
    <h3>
        {{ $title }}
        @if(request()->department)
            of {{ request()->department }}
        @endif
        @if(request()->date_from)
            from {{ request()->date_from }}-{{ request()->month }}-{{ request()->year }}
        @endif
        @if(request()->date_to)
            to {{ request()->date_to }}-{{ request()->month }}-{{ request()->year }}
        @endif
    </h3>
</div>

{{--<table id="search-info" style="width: 100%; background-color: transparent; padding-bottom: 15px">--}}
{{--    <tr>--}}
{{--        <th>Date From:</th>--}}
{{--        <td>1</td>--}}
{{--        <th>Date To:</th>--}}
{{--        <td>1</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th>Month-Year:</th>--}}
{{--        <td>January - 2024</td>--}}
{{--        <th>Department:</th>--}}
{{--        <td> All</td>--}}
{{--    </tr>--}}
{{--</table>--}}

<div class="card-body">
    <table id="table" class="table table-striped" style="width: 100%">
        <thead style="">
        <tr>
            <th>Serial</th>
            <th>User ID</th>
            <th>User Name</th>
            <th>Total Attendance</th>
        </tr>
        </thead>
        <tbody>
        @php
            $currentDepartment = null;
            $departmentId = 0;
            $departmentCounters = [];
        @endphp

        @foreach ($monthly_attendance_reports as $item)
            @if ($currentDepartment !== $item->department)
                @php
                    $currentDepartment = $item->department;
                    $departmentId++;
                    $departmentCounters[$currentDepartment] = 0; // Reset the department-specific counter
                @endphp
                <tr style="background-color: red">
                    <td colspan="4" class="text-danger bg-danger"><strong>{{ $departmentId }}
                            - {{ $currentDepartment }}</strong></td>
                </tr>
            @endif
            <tr>
                <td>
                    {{ ++$departmentCounters[$currentDepartment] }}
                </td>
                <td>{{ $item->user_id }}</td>
                <td><b>{{ fetchUserById($item->user_id) ?? 'NOT AVAILABLE' }}</b></td>
                <td>{{ $item->days_attended }} days</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
