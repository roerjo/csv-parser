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
        $path = $request->file('csv_data')->storeAs('csv-files', '1.csv');

        ParseReviewers::dispatch($path);

        return response()->view('home')->setStatusCode(204);
    }
}
