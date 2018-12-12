<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Events\ReviewerParsed;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ParseReviewers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Uploaded csv file
     *
     * @var Illuminate\Http\UploadedFile
     */
    private $csvFile;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Http\Uploaded $csvFile
     * @return void
     */
    public function __construct(UploadedFile $csvFile)
    {
        $this->csvFile = $csvFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reviewers = csvToArray($this->csvFile);

        foreach($reviewers as $reviewer) {
            $this->addFields($reviewer);
            $this->validate($reviewer);
            $this->resolveInviteStatus($reviewer);

            event(new ReviewerParsed($reviewer));
        }
    }

    private function addFields(&$reviewer)
    {
        $additionalFields = [
            'invite_sent'   => false,
            'invite_method' => null,
            'invite_type'   => null,
            'errors'        => [],
        ];

        $reviewer = array_merge($reviewer, $additionalFields);
    }

    private function validate(&$reviewer)
    {
        //
    }

    private function resolveInviteStatus(&$reviewer)
    {
        //
    }
}
