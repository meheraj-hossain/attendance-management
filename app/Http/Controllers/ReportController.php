<?php

namespace App\Http\Controllers;

use App\Models\Admin\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function monthly_attendance(Request $request, $monthName)
    {
        $yearMonth          = convertMonthNameToYearMonth($monthName);
        $data['title']      = 'Monthly Attendance Report of ' . $monthName;
        $data['month_name'] = $monthName;
        $dateQuery          = '';
        $userQuery          = '';
        if ($request->date_from) {
            $dateQuery = 'WHERE DAY(server_record_time) >= ' . $request->date_from;
        }
        if ($request->date_to) {
            $dateQuery = 'WHERE DAY(server_record_time) <= ' . $request->date_to;
        }
        if ($request->date_from && $request->date_to) {
            $dateQuery = 'WHERE DAY(server_record_time) BETWEEN ' . $request->date_from . ' AND ' . $request->date_to;
        }
        if ($request->user_id) {
            if ($request->date_from || $request->date_to) {
                $userQuery = 'AND user_id = ' . $request->user_id;
            } else {
                $userQuery = 'WHERE user_id = ' . $request->user_id;
            }
        }

        $data['users'] = DB::connection('odbc')->select("SELECT user_id, name FROM users GROUP BY user_id, name ORDER BY user_id ASC");

        $data['monthly_attendance_reports'] = DB::connection('odbc')
                                                ->select("SELECT
                b.user_id,
                MAX(b.user_name) AS user_name,
                b.month_name,
                COUNT(DISTINCT CAST(b.server_record_time AS DATE)) AS days_attended
                FROM (
                    SELECT
                    a.user_id,
                    MAX(a.user_name) AS user_name,
                    server_record_time,
                    DATEPART(mm, server_record_time) AS month_name
                    FROM (
                            SELECT
                            user_id,
                            user_name,
                            CAST(server_record_time AS DATETIME) AS server_record_time
                            FROM auth_logs_$yearMonth
                            --WHERE DAY(server_record_time) BETWEEN 16 AND 31  -- Filter records from 10th to 20th
                            $dateQuery
                            $userQuery
                        ) a
                        GROUP BY a.user_id, a.server_record_time
                ) b
                GROUP BY b.user_id, b.month_name
                ORDER BY b.month_name ASC");

        return view('admin.layouts.reports.monthly_attendance', $data);
    }

    public function daily_attendance(Request $request)
    {
        return view('admin.layouts.reports.daily_attendance');
    }

    public function user_attendance(Request $request)
    {
        $query = '';
        $dateQuery          = '';
        $monthQuery = $request->month;

        if ($request->date_from) {
            $dateQuery = 'WHERE DAY(server_record_time) >= ' . $request->date_from;
        }
        if ($request->date_to) {
            $dateQuery = 'WHERE DAY(server_record_time) <= ' . $request->date_to;
        }
        if ($request->date_from && $request->date_to) {
            $dateQuery = 'WHERE DAY(server_record_time) BETWEEN ' . $request->date_from . ' AND ' . $request->date_to;
        }
        if ($request->user_id) {
            if ($request->date_from || $request->date_to) {
                $userQuery = 'AND user_id = ' . $request->user_id;
            } else {
                $userQuery = 'WHERE user_id = ' . $request->user_id;
            }
        }

        if ($request->date_from || $request->date_to || $request->user_id) {
            $query = DB::connection('odbc')->select("
            SELECT user_id, user_name,
       CAST(server_record_time AS DATE) AS event_date,
       MIN(CASE WHEN terminal_name = 'In' THEN server_record_time ELSE NULL END) AS in_time,
	   MAX(CASE WHEN terminal_name = 'Out' THEN server_record_time ELSE NULL END) AS out_time,
	   COUNT(CASE WHEN terminal_name = 'In' THEN 1 ELSE NULL END) AS in_count,
	   COUNT(CASE WHEN terminal_name = 'Out' THEN 1 ELSE NULL END) AS out_count
FROM auth_logs_2023$monthQuery
$dateQuery
$userQuery
GROUP BY user_id, CAST(server_record_time AS DATE), user_name
ORDER BY user_id
            ");
        }

//        $user_name_query = \Illuminate\Support\Facades\DB::connection('odbc')
//                                                         ->select("SELECT user_id, user_name FROM auth_logs_2023" . request()->get('month') . " WHERE user_id = " . $user->user_id . " AND user_name IS NOT NULL AND user_name <> '' GROUP BY user_id, user_name");
//        dd($user_name_query);
        $data['title']            = 'User Attendance Report';
        $data['users']            = DB::connection('odbc')->select("SELECT user_id, name FROM users GROUP BY user_id, name ORDER BY user_id ASC");
        $data['users_attendance'] = $query;
//        dd($data['users_attendance']);
        return view('admin.layouts.reports.user_attendance', $data);
    }

}
