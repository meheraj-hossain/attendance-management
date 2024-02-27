<?php

namespace App\Http\Controllers;

use App\Models\Admin\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ReportController extends Controller
{
//    public function daily_attendance()
//    {
//        $sql              = 'select a.user_id, max(event_time),event_date from (select user_id, event_time, cast(event_time as date)  event_date from auth_logs_202310 where user_id = ' . $id . ') a group by event_date,user_id';

//    }

//    public function user_attendance($id)
//    {
//        $sql              = 'select a.user_id, max(event_time),event_date from (select user_id, event_time, cast(event_time as date)  event_date from auth_logs_202310 where user_id = ' . $id . ') a group by event_date,user_id';
//    }

//    public function monthly_attendance()
//    {
    //total days attended
//        $data = DB::connection('odbc')
//                  ->select("SELECT
//    b.user_id,
//    MAX(b.user_name) AS user_name,
//    b.month_name,
//    COUNT(DISTINCT CAST(b.server_record_time AS DATE)) AS days_attended
//FROM (
//    SELECT
//        a.user_id,
//        MAX(a.user_name) AS user_name,
//        server_record_time,
//        DATEPART(mm, server_record_time) AS month_name
//    FROM (
//        SELECT
//            user_id,
//            user_name,
//            CAST(server_record_time AS DATETIME) AS server_record_time
//        FROM auth_logs_202311
//    ) a
//    GROUP BY a.user_id, a.server_record_time
//) b
//GROUP BY b.user_id, b.month_name
//ORDER BY b.month_name ASC");

    //First IN
//        $dateToSearch = '2023-10-30';
//
//        $data1 = DB::connection('odbc')->select("SELECT TOP 1 server_record_time
//FROM auth_logs_202310
//WHERE user_id = 50
//AND terminal_name = 'In'
//AND CONVERT(DATE, server_record_time) = :dateToSearch
//ORDER BY server_record_time", ['dateToSearch' => $dateToSearch]);

    //Last OUT
//        $dateToSearch = '2023-10-30'; // The date you want to search for

//        $data2 = DB::connection('odbc')->select("SELECT TOP 1 server_record_time
//FROM auth_logs_202310
//WHERE user_id = 50
//AND terminal_name = 'Out'
//AND CONVERT(DATE, server_record_time) = :dateToSearch
//ORDER BY server_record_time DESC", ['dateToSearch' => $dateToSearch]);

    //Total Hours Worked
    // Assuming you have the two queries already stored in $data1 and $data2
//        $time_in = new \DateTime($data1[0]->server_record_time);
//        $time_out = new \DateTime($data2[0]->server_record_time);

// Calculate the time difference
//        $time_difference = $time_out->diff($time_in);

// Extract hours and minutes from the time difference
//        $hours_worked = $time_difference->h;
//        $minutes_worked = $time_difference->i;

//        $databaseName = DB::connection('odbc')->select("SELECT table_name
//FROM information_schema.tables
//WHERE table_name LIKE 'auth_logs_%'");
//        $tableNames = DB::select("
//        SELECT table_name
//        FROM information_schema.tables
//        WHERE table_name LIKE 'auth_logs_%' AND table_catalog = '$databaseName'
//    ");
//        $monthNames = app('TableNameToMonthName')->getMonthNamesFromDatabase();
//        $monthNames = app('TableNameToMonthName')->getMonthNameFromDatabase();

//    }

    public function monthly_attendance_date_wise(Request $request)
    {
        $year                = $request->year;
        $month               = $request->month;
        $yearMonth           = $year . $month;
        $initialDatabaseName = "auth_logs_" . date('Ym');
        $databaseName        = 'auth_logs_' . $yearMonth;
        $dateQuery           = '';
        $department          = '';
        $initialStartDate = now()->firstOfMonth()->toDateString();
        $initialEndDate = now()->firstOfMonth()->addMonth()->toDateString();

        if ($request->date_from) {
            $dateQuery = 'AND DAY(event_time) >= ' . $request->date_from;
        }
        if ($request->date_to) {
            $dateQuery = 'AND DAY(event_time) <= ' . $request->date_to;
        }
        if ($request->date_from && $request->date_to) {
            $dateQuery = 'AND DAY(event_time) BETWEEN ' . $request->date_from . ' AND ' . $request->date_to;
        }
        if ($request->department) {
            $department = " AND u.department = '" . $request->department . "'";
        }

        $initialDistinctDates = DB::connection('odbc')->select("
    SELECT ROW_NUMBER() OVER (ORDER BY CAST(DATEADD(HOUR, -6, event_time) AS DATE)) AS column_number,
           CAST(DATEADD(HOUR, -6, event_time) AS DATE) AS date
    FROM $initialDatabaseName
    WHERE DATEADD(HOUR, -6, event_time) >= '$initialStartDate' AND DATEADD(HOUR, -6, event_time) < '$initialEndDate'
    GROUP BY CAST(DATEADD(HOUR, -6, event_time) AS DATE)
    ORDER BY date ASC
");

        $initialCols = [];
        foreach ($initialDistinctDates as $row) {
            $initialCols[] = "MAX(CASE WHEN CAST(DATEADD(HOUR, -6, a.event_time) AS DATE) = '" . $row->date . "' THEN 'present' ELSE 'absent' END) AS '" . $row->column_number . "'";
        }
        $initialCols = implode(', ', $initialCols);

        $query = DB::connection('odbc')->select("
    SELECT a.user_id, u.department, COUNT(DISTINCT CAST(DATEADD(HOUR, -6, a.event_time) AS DATE)) AS days_attended, $initialCols
    FROM $initialDatabaseName a
    JOIN users u ON a.user_id = u.user_id
    WHERE a.user_name <> ''
    AND CAST(DATEADD(HOUR, -6, event_time) AS DATE) >= '" . now()->firstOfMonth()->toDateString() . "'
    AND u.department <> ''
    $dateQuery
    $department
    GROUP BY a.user_id, u.department
    ORDER BY u.department, a.user_id ASC, MIN(CAST(DATEADD(HOUR, -6, a.event_time) AS DATE)) ASC
");



        if ($request->date_from || $request->date_to || $request->department || $request->year || $request->month) {
            if (in_array($databaseName, app('TableNameToMonthName')->arrayTables())) {
                $StartDate = Carbon::create($year, $month, 1)->startOfDay();
                $EndDate = $StartDate->copy()->addMonth()->startOfDay();

                $distinctDates = DB::connection('odbc')->select("
    SELECT ROW_NUMBER() OVER (ORDER BY CAST(DATEADD(HOUR, -6, event_time) AS DATE)) AS column_number,
           CAST(DATEADD(HOUR, -6, event_time) AS DATE) AS date
    FROM $databaseName
    WHERE DATEADD(HOUR, -6, event_time) >= '$StartDate' AND DATEADD(HOUR, -6, event_time) < '$EndDate'
    GROUP BY CAST(DATEADD(HOUR, -6, event_time) AS DATE)
    ORDER BY date ASC
");

                $cols = [];
                foreach ($distinctDates as $row) {
                    $cols[] = "MAX(CASE WHEN CAST(DATEADD(HOUR, -6, a.event_time) AS DATE) = '" . $row->date . "' THEN 'present' ELSE 'absent' END) AS '" . $row->column_number . "'";
                }
                $cols = implode(', ', $cols);
                $query = DB::connection('odbc')
                           ->select("
                                           SELECT a.user_id, u.department, COUNT(DISTINCT CAST(DATEADD(HOUR, -6, a.event_time) AS DATE)) AS days_attended, $cols
    FROM $databaseName a
    JOIN users u ON a.user_id = u.user_id
    WHERE a.user_name <> ''
    AND CAST(DATEADD(HOUR, -6, event_time) AS DATE) >= '" . $StartDate . "'
    AND u.department <> ''
    $dateQuery
    $department
    GROUP BY a.user_id, u.department
    ORDER BY u.department, a.user_id ASC, MIN(CAST(DATEADD(HOUR, -6, a.event_time) AS DATE)) ASC
");
            } else {
                Alert::error('Error', 'Please select a valid Month and Year!');

                return redirect()->back();
            }
        }

        $data['title']                      = ($request->year && $request->month) ? 'Monthly Attendance Report in ' . date('F', mktime(0, 0, 0, $request->month, 1)) . '-' . $request->year : 'Monthly Attendance Report in ' . date('F-Y');
        $data['monthly_attendance_reports'] = $query;
//        dd($data['monthly_attendance_reports']);

        return view('admin.layouts.reports.monthly_attendance_date_wise', $data);
    }

    public function monthly_attendance(Request $request)
    {
        $year                = $request->year;
        $month               = $request->month;
        $yearMonth           = $year . $month;
        $initialDatabaseName = "auth_logs_" . date('Ym');
        $databaseName        = 'auth_logs_' . $yearMonth;
        $dateQuery           = '';
        $department          = '';

        if ($request->date_from) {
            $dateQuery = 'AND DAY(event_time) >= ' . $request->date_from;
        }
        if ($request->date_to) {
            $dateQuery = 'AND DAY(event_time) <= ' . $request->date_to;
        }
        if ($request->date_from && $request->date_to) {
            $dateQuery = 'AND DAY(event_time) BETWEEN ' . $request->date_from . ' AND ' . $request->date_to;
        }
        if ($request->department) {
            $department = " AND u.department = '" . $request->department . "'";
        }

        $query = DB::connection('odbc')
                   ->select("
                                    SELECT a.user_id,  u.department, COUNT(DISTINCT CAST(DATEADD(HOUR, -6, a.event_time) AS DATE)) AS days_attended
                                    FROM $initialDatabaseName a
                                    JOIN users u ON a.user_id = u.user_id
                                    WHERE a.user_name <> ''
                                    AND CAST(DATEADD(HOUR, -6, event_time) AS DATE) >= '" . date('Y-m-01') . "'
                                    AND u.department <> ''
                                    $dateQuery
                                    $department
                                    GROUP BY a.user_id, u.department ORDER BY u.department, a.user_id ASC
");

        if ($request->date_from || $request->date_to || $request->department || $request->year || $request->month) {
            if (in_array($databaseName, app('TableNameToMonthName')->arrayTables())) {
                $query = DB::connection('odbc')
                           ->select("
                                            SELECT a.user_id,  u.department, COUNT(DISTINCT CAST(DATEADD(HOUR, -6, a.event_time) AS DATE)) AS days_attended
                                            FROM $databaseName a
                                            JOIN users u ON a.user_id = u.user_id
                                            WHERE a.user_name <> ''
                                            AND CAST(DATEADD(HOUR, -6, event_time) AS DATE) >= '" . $year . '-' . $month . '-' . '01' . "'
                                            AND u.department <> ''
                                            $dateQuery
                                            $department
                                            GROUP BY a.user_id, u.department ORDER BY u.department, a.user_id ASC
");
            } else {
                Alert::error('Error', 'Please select a valid Month and Year!');

                return redirect()->back();
            }
        }

        $data['title']                      = ($request->year && $request->month) ? 'Monthly Attendance Report in ' . date('F', mktime(0, 0, 0, $request->month, 1)) . '-' . $request->year : 'Monthly Attendance Report in ' . date('F-Y');
        $data['monthly_attendance_reports'] = $query;
//        dd($data['monthly_attendance_reports']);

        return view('admin.layouts.reports.monthly_attendance', $data);
    }

    public function daily_attendance(Request $request)
    {
        $year                = $request->year;
        $month               = $request->month;
        $yearMonth           = $year . $month;
        $initialDatabaseName = "auth_logs_" . date('Ym');
        $databaseName        = 'auth_logs_' . $yearMonth;
        $dateQuery           = $initialDatabaseName ? 'AND DAY(event_time) = ' . date('d') : 'AND DAY(event_time) =  1';
        $department          = '';

        if ($request->date_from) {
            $dateQuery = 'AND DAY(CAST(DATEADD(HOUR, -6, event_time) AS DATE)) >= ' . $request->date_from;
        }
        if ($request->date_to) {
            $dateQuery = 'AND DAY(CAST(DATEADD(HOUR, -6, event_time) AS DATE)) <= ' . $request->date_to;
        }
        if ($request->date_from && $request->date_to) {
            $dateQuery = 'AND DAY(CAST(DATEADD(HOUR, -6, event_time) AS DATE)) BETWEEN ' . $request->date_from . ' AND ' . $request->date_to;
        }
        if ($request->department) {
            $department = " AND u.department = '" . $request->department . "'";
        }

        $query = DB::connection('odbc')
                   ->select("
            SELECT
            al.user_id,
            u.department,
            CAST(DATEADD(HOUR, -6, al.event_time) AS DATE) AS modified_event_time,
            MIN(CASE WHEN al.terminal_name = 'FACE IN' THEN DATEADD(HOUR, -6, al.event_time) ELSE NULL END) AS modified_in_time,
            MAX(CASE WHEN al.terminal_name = 'FACE Out' THEN DATEADD(HOUR, -6, al.event_time) ELSE NULL END) AS modified_out_time,
            MIN(CASE WHEN al.terminal_name = 'FACE IN' THEN al.event_time ELSE NULL END) AS in_time,
            COUNT(CASE WHEN al.terminal_name = 'FACE IN' THEN 1 END) AS total_in_count,
            COUNT(CASE WHEN al.terminal_name = 'FACE Out' THEN 1 END) AS total_out_count
        FROM
            $initialDatabaseName al
        JOIN
            users u ON al.user_id = u.user_id
        WHERE
            al.user_name <> ''
            $dateQuery
            $department
        GROUP BY
            al.user_id, u.department, CAST(DATEADD(HOUR, -6, al.event_time) AS DATE)
        ORDER BY
           al.user_id, CAST(DATEADD(HOUR, -6, al.event_time) AS DATE);
");

        if ($request->date_from || $request->date_to || $request->department || $request->year || $request->month) {
            if (in_array($databaseName, app('TableNameToMonthName')->arrayTables())) {
                $query = DB::connection('odbc')
                           ->select("
            SELECT
            al.user_id,
            u.department,
            CAST(DATEADD(HOUR, -6, al.event_time) AS DATE) AS modified_event_time,
            MIN(CASE WHEN al.terminal_name = 'FACE IN' THEN DATEADD(HOUR, -6, al.event_time) ELSE NULL END) AS modified_in_time,
            MAX(CASE WHEN al.terminal_name = 'FACE Out' THEN DATEADD(HOUR, -6, al.event_time) ELSE NULL END) AS modified_out_time,
            MIN(CASE WHEN al.terminal_name = 'FACE IN' THEN al.event_time ELSE NULL END) AS in_time,
            COUNT(CASE WHEN al.terminal_name = 'FACE IN' THEN 1 END) AS total_in_count,
            COUNT(CASE WHEN al.terminal_name = 'FACE Out' THEN 1 END) AS total_out_count
        FROM
            $databaseName al
        JOIN
            users u ON al.user_id = u.user_id
        WHERE
            al.user_name <> ''
            $dateQuery
            $department
        GROUP BY
            al.user_id, u.department, CAST(DATEADD(HOUR, -6, al.event_time) AS DATE)
        ORDER BY
           al.user_id, CAST(DATEADD(HOUR, -6, al.event_time) AS DATE);
");
            } else {
                Alert::error('Error', 'Please select a valid Month and Year!');

                return redirect()->back();
            }
        }
        $data['title'] = ($request->year && $request->month) ? 'Daily Attendance Report in ' . date('F', mktime(0, 0, 0, $request->month, 1)) . '-' . $request->year : 'Daily Attendance Report in ' . date('F-Y');
        if (request()->department) {
            $data['title'] .= ' of ' . request()->department;
        }
        if (request()->date_from) {
            $data['title'] .= ' from ' . request()->date_from . '-' . request()->month . '-' . request()->year;
        }
        if (request()->date_to) {
            $data['title'] .= ' to ' . request()->date_to . '-' . request()->month . '-' . request()->year;
        }
        $data['daily_attendance_reports'] = $query;

        return view('admin.layouts.reports.daily_attendance', $data);
    }

    public function user_attendance(Request $request)
    {
        $year         = $request->year;
        $month        = $request->month;
        $yearMonth    = $year . $month;
        $databaseName = 'auth_logs_' . $yearMonth;
        $query        = '';
        $dateQuery    = '';
        $userQuery    = '';

        if ($request->date_from) {
            $dateQuery = ' AND DAY(CAST(DATEADD(HOUR, -6, event_time) AS DATE)) >= ' . $request->date_from;
        }
        if ($request->date_to) {
            $dateQuery = 'AND DAY(CAST(DATEADD(HOUR, -6, event_time) AS DATE)) <= ' . $request->date_to;
        }
        if ($request->date_from && $request->date_to) {
            $dateQuery = 'AND DAY(CAST(DATEADD(HOUR, -6, event_time) AS DATE)) BETWEEN ' . $request->date_from . ' AND ' . $request->date_to;
        }
        if ($request->user_id) {
            $userQuery = 'AND user_id = ' . $request->user_id;
        }
        if ($request->date_from || $request->date_to || $request->user_id || $request->year || $request->month) {
            if (in_array($databaseName, app('TableNameToMonthName')->arrayTables())) {
                if ($request->user_id) {
                    $query = DB::connection('odbc')->select("
    SELECT
        user_id,
        CAST(DATEADD(HOUR, -6, event_time) AS DATE) AS modified_event_time,
        MIN(CASE WHEN terminal_name = 'FACE IN' THEN DATEADD(HOUR, -6, event_time) ELSE NULL END) AS modified_in_time,
        MAX(CASE WHEN terminal_name = 'FACE Out' THEN DATEADD(HOUR, -6, event_time) ELSE NULL END) AS modified_out_time,
        MIN(CASE WHEN terminal_name = 'FACE IN' THEN event_time ELSE NULL END) AS in_time,
        COUNT(CASE WHEN terminal_name = 'FACE IN' THEN 1 END) AS total_in_count,
        COUNT(CASE WHEN terminal_name = 'FACE Out' THEN 1 END) AS total_out_count
    FROM
        $databaseName
    WHERE user_name <> ''
    $dateQuery
    $userQuery
    GROUP BY
        user_id, CAST(DATEADD(HOUR, -6, event_time) AS DATE)
    ORDER BY
        user_id
");
                } else {
                    Alert::error('Error', 'Please Select a user!');

                    return redirect()->back();
                }
            } else {
                Alert::error('Error', 'Please select a valid Month and Year!');

                return redirect()->back();
            }
        }
        $data['title'] = 'User Attendance Report';
        if (request()->user_id) {
            $data['title'] .= ' of ' . fetchUserById(request()->user_id);
        }
        if (request()->date_from) {
            $data['title'] .= ' from ' . request()->date_from . '-' . request()->month . '-' . request()->year;
        }
        if (request()->date_to) {
            $data['title'] .= ' to ' . request()->date_to . '-' . request()->month . '-' . request()->year;
        }

        if ($request->month && $request->year) {
            $data['title'] .= ' in ' . date('F', mktime(0, 0, 0, $request->month, 1)) . '-' . $request->year;
        }

        $data['users_attendance'] = $query;

        return view('admin.layouts.reports.user_attendance', $data);
    }

    public function monthly_attendance2(Request $request)
    {
        $department             = [];
        $departments_from_users = DB::connection('odbc')->select("SELECT department FROM users WHERE department <> '' AND department IN ( 'Reporting','IT') GROUP BY department ORDER BY department ASC");
        foreach ($departments_from_users as $department_from_users) {
            $department[] = $department_from_users->department;
        }
        $query                               = DB::connection('odbc')->select(
            "SELECT a.user_id,  u.department, COUNT(DISTINCT CAST(DATEADD(HOUR, -6, a.event_time) AS DATE)) AS days_attended
FROM auth_logs_202312 a
JOIN users u ON a.user_id = u.user_id
WHERE a.user_name <> ''
AND CAST(DATEADD(HOUR, -6, event_time) AS DATE) >= '2023-12-01'
AND u.department <> ''
AND u.department IN ('IT')
GROUP BY a.user_id, u.department ORDER BY u.department, a.user_id ASC"
        );
        $data['title']                       = 'Monthly Attendance2 Report';
        $data['monthly_attendance2_reports'] = $query;
        return view('admin.layouts.reports.monthly_attendance2', $data);
    }

}
