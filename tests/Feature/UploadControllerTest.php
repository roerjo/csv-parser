<?php

namespace Tests\Feature;

use Tests\TestCase;
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

        $response
            ->assertRedirect('/')
            ->assertSessionHasErrors('csv_data');
    }
}
