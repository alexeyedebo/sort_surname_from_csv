<?php

define('NUMBER_OF_PARTS', 2); // how many arrays will be created
define('NEW_FILE_NAME', 'file');

$filename = 'test.csv'; // number, first_name, last_name

// get csv data
$fp = @fopen($filename, 'r') or die("can't open " . $filename . " for import data");

while ($csv_line = fgetcsv($fp)) {
    if ($csv_line[0] != '') {
        $array[] = $csv_line;
    }
}

@fclose($fp);

if (!empty($array)) {

    // sort array
    function build_sorter($key)
    {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }
    usort($array, build_sorter(2));  // 2 - sort by last_name



    // separation array
    $cnt = count($array);
    $k = ($cnt % NUMBER_OF_PARTS == 0) ? $cnt / NUMBER_OF_PARTS : $cnt / NUMBER_OF_PARTS + 1;
    $array = array_chunk($array, $k, true);

    // save in files
    $i = 1;
    foreach ($array as $arr) {
        // create new file
        $new_filename = NEW_FILE_NAME . $i . '.csv';
        $f = @fopen($new_filename, 'w') or die("can't open or create " . $new_filename);

        // save data
        foreach ($arr as $item) {
            fputcsv($f, array_map('trim', $item));
        }
        @fclose($f);
        $i++;
    }
    print('The data was saved');
} else {
    print('The file ' . $filename . ' doesn\'t contain data');
}