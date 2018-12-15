<?php

namespace Tests\Feature;

use Bus;
use Storage;
use Tests\TestCase;
use App\Jobs\ParseReviewers;
use Illuminate\Http\UploadedFile;

class ParserControllerTest extends TestCase
{
    /**
     * Ensure validation is performed on upload data
     *
     * @return void
     */
    public function testItFailsValidation()
    {
        $badFile = UploadedFile::fake()->create('test.jpg')->size(100);

        $response = $this->post(
            '/parser',
            ['csv_data' => $badFile]
        );

        //dd($response);
        $response
            ->assertRedirect('/')
            ->assertSessionHasErrors('csv_data');
    }

    /*
     * Ensure csv file passes validation and job is dispatched
     *
     * @return void
     */
    public function testItPassesValidation()
    {
        Bus::fake();
        Storage::fake();

        $csvFile = new UploadedFile('tests/test-data.csv', 'test-data', 'text/csv', null, true);

        $response = $this->post(
            '/parser',
            ['csv_data' => $csvFile]
        );

        //dd($response);
        $response->assertStatus(204);

        Storage::assertExists('csv-files/test-data.csv');
        Bus::assertDispatched(ParseReviewers::class);
    }
}
