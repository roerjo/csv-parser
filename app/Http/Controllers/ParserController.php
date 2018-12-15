<?php

namespace App\Http\Controllers;

use Storage;
use App\Jobs\ParseReviewers;
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
        $path = Storage::putFileAs(
            'csv-files',
            $request->file('csv_data'),
            $request->file('csv_data')->getClientOriginalName().'.csv'
        );

        ParseReviewers::dispatch($path);

        return response()->view('home')->setStatusCode(204);
    }
}
