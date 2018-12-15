<?php
if (!function_exists('csvToArray')) {
    /**
     * Transform csv file into collection
     *
     * @param string $csvContents
     * @return array $csvArr
     */
    function csvToArray(string $csvContents)
    {
        // convert the csv file into an array
        $csvArr = array_map('str_getcsv', explode(PHP_EOL, $csvContents));

        // set keys of the array equal to the header values
        array_walk($csvArr, function (&$row) use ($csvArr) {
            $row = array_combine($csvArr[0], $row);
        });

        // remove the header row
        array_shift($csvArr);

        return $csvArr;
    }
}
