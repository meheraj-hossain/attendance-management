<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function daily_attendance($day, $id)
    {
//        $sql              = 'select a.user_id, max(event_time),event_date from (select user_id, event_time, cast(event_time as date)  event_date from auth_logs_202310 where user_id = ' . $id . ') a group by event_date,user_id';

    }

    public function user_attendance($id)
    {
//        $sql              = 'select a.user_id, max(event_time),event_date from (select user_id, event_time, cast(event_time as date)  event_date from auth_logs_202310 where user_id = ' . $id . ') a group by event_date,user_id';
    }

    public function monthly_attendance()
    {
        //total days attended
        $data = DB::connection('odbc')
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
        FROM auth_logs_202311
    ) a
    GROUP BY a.user_id, a.server_record_time
) b
GROUP BY b.user_id, b.month_name
ORDER BY b.month_name ASC");

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


    dd($data);
    }
}