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
        $data = DB::connection('odbc')
                  ->select("SELECT
                b.user_id,
                MAX(b.user_name) AS user_name,
                b.month_name,
                SUM(1) AS days_attended
              FROM (SELECT
                      a.user_id,
                      MAX(a.user_name) AS user_name,
                      event_date,
                      DATEPART(mm, event_date) AS month_name
                    FROM (SELECT
                            user_id,
                            user_name,
                            CAST(event_time AS DATE) AS event_date
                          FROM auth_logs_202310) a
                    GROUP BY user_id, event_date) b
              GROUP BY b.user_id, b.month_name");
        dd($data);
    }
}
