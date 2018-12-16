<?php
if (!function_exists('csvToArray')) {
    /**
     * Transform csv file into an associative array
     *
     * @param string $csvContents
     * @return array $csvArr
     */
    function csvToArray(string $csvContents)
    {
        // Some text editors add a new line to the end of the CSV file which
        // can cause this function to think there is an additional empty row.
        // Trimming all trailing whitespace to be sure that doesn't happen.
        $csvContents = rtrim($csvContents);

        // convert the csv file into an array
        $csvArr = array_map('str_getcsv', explode(PHP_EOL, $csvContents));

        // set keys of the array equal to the header values
        array_walk($csvArr, function (&$row) use ($csvArr) {
            if (count($csvArr[0]) != count($row)) {
                dd($csvArr[0], $row);
            }
            $row = array_combine($csvArr[0], $row);
        });

        // remove the header row
        array_shift($csvArr);

        return $csvArr;
    }
}
