<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



function csv_to_array($csvfile, $csvhead=true, $delimiter=";", $enclosure='"', $rowlength=0) {
// --------------------------------------     
    $csvdata = array();
    // ---------------------------- 
    if (file_exists($csvfile)) {
        $ReadHandle = @fopen($csvfile, 'r');
        if (true === $csvhead) { $headrow = fgetcsv($ReadHandle, $rowlength, $delimiter, $enclosure); }
        // ---------------------
        while($line = fgetcsv($ReadHandle, $rowlength, $delimiter, $enclosure)) {
            if (true === $csvhead)
            {
                foreach ($headrow as $key => $heading)
                {
                    if (!empty($heading)) { $row[$heading] = (isset($line[$key])) ? $line[$key] : ''; }
                }
                if (!empty($row)) { $csvdata[] = $row; }
            } else
            {
                if (!empty($line)) { $csvdata[] = $line; }
            }
        }
        // ---------------------
        @fclose($ReadHandle);
    }
    // ---------------------------- 
    return $csvdata;
// -------------------------------------- 
} 
