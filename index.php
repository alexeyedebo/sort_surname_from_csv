<?php

define('NUMBER_OF_PARTS', 2);

$filename = 'test.csv'; // number, first_name, last_name

// get csv data
$fp = fopen($filename, 'r') or die("can't open file");

$csv_line = fgetcsv($fp);
while ($csv_line = fgetcsv($fp)) {
    if ($csv_line[0] != '') {
        $array[] = $csv_line;
    }
}

fclose($fp) or die("can't close file");

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

//save in file
$i = 1;
foreach ($array as $arr) {
    $f = fopen('file' . $i . '.csv', 'a');
    foreach ($arr as $item) {
        fputcsv($f, array_map('trim', $item));
    }
    fclose($f);
    $i++;
}