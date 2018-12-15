<?php

namespace Tests\Feature\Jobs;

use Event;
use Storage;
use Tests\TestCase;
use App\Jobs\ParseReviewers;
use App\Events\ReviewerParsed;
use Illuminate\Http\UploadedFile;

class ParseReviewersTest extends TestCase
{
    /*
     * Ensure the ParseReviewers job fires the ReviewerParsed event
     *
     * @return void
     */
    public function testItParsesReviewers()
    {
        Event::fake();

        $csvFile = new UploadedFile('tests/test-data.csv', 'test-data', 'text/csv', null, true);
        $path = Storage::putFileAs(
            'csv-files',
            $csvFile,
            $csvFile->getClientOriginalName().'.csv'
        );

        (new ParseReviewers($path))->handle();

        Event::assertDispatched(ReviewerParsed::class);
    }
}
