<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CsvTransformer;
use App\Http\Requests\Parser\StoreRequest;

class ParserController extends Controller
{
    /**
     * Take care of incoming csv file
     *
     * @param StoreRequest $request
     * @return void
     */
    public function store(StoreRequest $request)
    {
        $csvFile = $request->file('csv_data');

        $potentialReviewers = CsvTransformer::transform($csvFile);
    }
}
