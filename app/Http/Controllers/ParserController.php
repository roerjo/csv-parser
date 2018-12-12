<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CsvTransformer;
use App\Http\Requests\Parser\StoreRequest;
use App\Jobs\ParseReviewers;

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

        ParseReviewers::dispatch($csvFile);

        return response()->view('home')->setStatusCode(204);
    }
}
