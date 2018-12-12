<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class CsvTransformer
{
    /**
     * Transform csv file into collection
     *
     * @param UploadedFile $csvFile
     * @return \Illuminate\Support\Collection
     */
    public static function transform(UploadedFile $csvFile)
    {
        // convert the csv file into an array
        $csvArr = array_map('str_getcsv', file($csvFile));

        // set keys of the array equal to the header values
        array_walk($csvArr, function (&$row) use ($csvArr) {
            $row = array_combine($csvArr[0], $row);
        });

        // remove the header row
        array_shift($csvArr);

        return collect($csvArr);
    }
}
