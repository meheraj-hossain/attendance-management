<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PdfController extends Controller
{
    public function generate_pdf(Request $request)
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

        $data['title']                      = ($request->year && $request->month)
            ? 'Monthly Attendance Report in ' . date('F', mktime(0, 0, 0, $request->month, 1)) . '-' . $request->year
            : 'Monthly Attendance Report in ' . date('F-Y');
        $data['monthly_attendance_reports'] = $query;

        // Load the PDF view using the laravel-dompdf package
        $pdf = Pdf::loadView('admin.layouts.generators.pdf_monthly_reports', $data)->setPaper('a4', 'portrait');
        // Download the generated PDF with a specific filename
        return $pdf->download(($request->year && $request->month)
            ? 'Monthly-Attendance-Report-in-' . date('F', mktime(0, 0, 0, $request->month, 1)) . '-' . $request->year
            : 'Monthly-Attendance-Report-in-' . date('F-Y'));
//        return view('admin.layouts.generators.pdf_monthly_reports', $data);
    }

    public function generate_monthly_attendance_date_wise_pdf(Request $request)
    {
        $year                = $request->year;
        $month               = $request->month;
        $yearMonth           = $year . $month;
        $initialDatabaseName = "auth_logs_" . date('Ym');
        $databaseName        = 'auth_logs_' . $yearMonth;
        $dateQuery           = '';
        $department          = '';
        $initialStartDate    = now()->firstOfMonth()->toDateString();
        $initialEndDate      = now()->firstOfMonth()->addMonth()->toDateString();

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
                $EndDate   = $StartDate->copy()->addMonth()->startOfDay();

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
                $cols  = implode(', ', $cols);
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

        $data['title'] = ($request->year && $request->month)
            ? 'Date Wise Monthly Attendance Report in ' . date('F', mktime(0, 0, 0, $request->month, 1)) . '-' . $request->year
            : 'Date Wise Monthly Attendance Report in ' . date('F-Y');

        $data['monthly_attendance_reports'] = $query;

        $pdf = Pdf::loadView('admin.layouts.generators.pdf_monthly_attendance_date_wise', $data)->setPaper('a3', 'landscape');

        return $pdf->download(($request->year && $request->month)
            ? 'Date-Wise-Monthly-Attendance-Report-in-' . date('F', mktime(0, 0, 0, $request->month, 1)) . '-' . $request->year
            : 'Date-Wise-Monthly-Attendance-Report-in-' . date('F-Y'));
//        return view('admin.layouts.generators.pdf_monthly_attendance_date_wise', $data);
    }

}
