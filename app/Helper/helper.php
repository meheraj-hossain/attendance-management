<?php
function convertMonthNameToYearMonth($monthName) : ?string
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

function dateString($dateStrings) : int
{
    $dateString = $dateStrings;
    list($day, $month, $year) = explode('/', $dateString);
    $day = intval($day);

// $day will now contain the day part
    return $day;
}
