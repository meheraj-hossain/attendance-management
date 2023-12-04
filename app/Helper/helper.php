<?php

use Illuminate\Support\Facades\DB;

function convertMonthNameToYearMonth($monthName): ?string
{
    // Split the month name into parts
    $parts = explode('-', $monthName);

    if (count($parts) === 2) {
        $monthName = $parts[0];
        $year      = $parts[1];

        // Create a DateTime object to parse the month name
        $date = DateTime::createFromFormat('F-Y', $monthName . '-' . $year);

        if ($date) {
            // Format the date as 'Ym' (e.g., '202309')
            return $date->format('Ym');
        }
    }

    return null; // Return null if the input format is invalid
}

function totalHourWorked($in_Time, $out_Time)
{
    $inTime  = new DateTime($in_Time);
    $outTime = new DateTime($out_Time);

    $timeDifference = $outTime->diff($inTime);

    $totalHours   = $timeDifference->h;
    $totalMinutes = $timeDifference->i;

    return $totalHours . ' Hours and ' . $totalMinutes . ' minutes';
}

function outTime($modified_In_Time, $modified_Out_Time, $in_Time)
{
    $modifiedInTime  = new DateTime($modified_In_Time);
    $modifiedOutTime = new DateTime($modified_Out_Time);
    $timeDifference  = $modifiedOutTime->diff($modifiedInTime);

    $totalHours   = $timeDifference->h;
    $totalMinutes = $timeDifference->i;

    $inTime       = $in_Time;
    $carbonInTime = \Carbon\Carbon::parse($inTime);
    $outTime      = ($carbonInTime->addHours($totalHours)->addMinutes($totalMinutes))->format('Y-m-d h:i A');

    return $outTime;
}

function fetchUser()
{
    return DB::connection('odbc')->select("SELECT user_id, name FROM users GROUP BY user_id, name ORDER BY user_id ASC");
}

function fetchDepartmentByUser($user_id)
{
    $result = DB::connection('odbc')->select("SELECT department FROM users WHERE user_id = '$user_id'");

    return $result[0]->department;
}

function fetchUserById($user_id)
{
    $result = DB::connection('odbc')->select("SELECT name FROM users WHERE user_id = '$user_id'");

    return $result[0]->name;
}

function fetchDepartment()
{
    return DB::connection('odbc')
             ->select("SELECT department FROM users WHERE department <> '' GROUP BY department ORDER BY department ASC");
}



//function monthNameToNumeric($monthName)
//{
//    // Attempt to parse the input string
//    $date = date_parse_from_format('F-Y', $monthName);
//
//    // Check if parsing was successful
//    if ($date['error_count'] === 0 && $date['warning_count'] === 0) {
//        $year  = $date['year'];
//        $month = str_pad($date['month'], 2, '0', STR_PAD_LEFT);
//
//        return $year . $month;
//    }
//
//    return null; // Handle the case where the input format is not as expected
//}

