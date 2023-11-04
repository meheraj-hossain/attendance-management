<?php
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

function getUserName($user_id)
{
    $monthQuery      = request()->get('month') ?? \Carbon\Carbon::now()->format('m');
    $user_name_query = \Illuminate\Support\Facades\DB::connection('odbc')->select("SELECT user_name FROM auth_logs_2023" . $monthQuery . " WHERE user_id =" . $user_id . " AND user_name IS NOT NULL AND user_name <> '' GROUP BY user_name");
    $user_name       = $user_name_query[0]->user_name ?? 'Unknown';

    return $user_name;
}

