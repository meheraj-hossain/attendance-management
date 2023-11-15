<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Class TableNameToMonthName.
 */
class TableNameToMonthName
{
    public function getMonthNameFromDatabase()
    {
        $allTableNames = DB::connection('odbc')->select("SELECT table_name
                                                            FROM information_schema.tables
                                                            WHERE table_name LIKE 'auth_logs_%'");
        $frontTableNames = array_slice($allTableNames, 2, 4 );
        $tableNames = array_reverse($frontTableNames);
        $monthNames = [];
        foreach ($tableNames as $table) {
            $tableName = $table->table_name;
            // Extract the year and month from the table name using regular expressions
            preg_match('/auth_logs_(\d{4})(\d{2})/', $tableName, $matches);
            if (count($matches) === 3) {
                $year  = $matches[1];
                $month = $matches[2];

                $date      = new \DateTime("{$year}-{$month}-01");
                $monthName = $date->format('F-Y');

                $monthNames[$tableName] = $monthName;
            }
        }
        return $monthNames;
    }
}
