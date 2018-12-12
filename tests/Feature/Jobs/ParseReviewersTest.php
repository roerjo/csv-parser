<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Jobs\ParseReviewers;
use Illuminate\Http\UploadedFile;

class ParseReviewersTest extends TestCase
{
    /*
     * Ensure csv file passes validation and job is dispatched
     *
     * @return void
     */
    public function testItParsesReviewers()
    {
        $csvFile = new UploadedFile('tests/test-data.csv', 'test-data', 'text/csv', null, true);

        (new ParseReviewers($csvFile))->handle();
    }
}
