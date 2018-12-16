<?php

namespace App\Jobs;

use Log;
use Storage;
use Validator;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Events\ReviewerParsed;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ParseReviewers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * File path to the CSV file
     *
     * @var string
     */
    private $csvPath;

    /**
     * Contents of the CSV file
     *
     * @var string
     */
    private $csvContents;

    /**
     * Reviewers to be parsed
     *
     * @var array
     */
    private $reviewers;

    /**
     * Create a new job instance.
     *
     * @param string $csvPath
     * @return void
     */
    public function __construct(string $csvPath)
    {
        $this->csvPath = $csvPath;
        $this->csvContents = Storage::get($csvPath);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->reviewers = csvToArray($this->csvContents);

        foreach($this->reviewers as $reviewer) {

            $this->reviewer = $reviewer;

            try {
                $this->addFields($reviewer);
                $this->validate($reviewer);

                if (empty($reviewer['errors'])) {
                    $this->resolveInviteStatus($reviewer);
                }

                event(new ReviewerParsed($reviewer));
            } catch (\Exception $e) {
                // don't let one bad reviewer prevent the rest of the array
                // from being parsed
                Log::error($e->getMessage());
                continue;
            }
        }

        Storage::delete($this->csvPath);
    }

    /**
     * Add the invite and errors fields to the reviewer array
     *
     * @param array $reviewer
     * @return void
     */
    private function addFields(array &$reviewer)
    {
        $additionalFields = [
            'invite_sent'   => false,
            'invite_method' => null,
            'invite_type'   => null,
            'errors'        => '',
        ];

        $reviewer = array_merge($reviewer, $additionalFields);
    }

    /**
     * Validate the reviewer data
     *
     * @param array $reviewer
     * @return void
     */
    private function validate(array &$reviewer)
    {
        // script runs as if today is March 5, 2018
        $now = now()->setDate(2018, 3, 5);
        $transDateTime = $reviewer['trans_date'].' '.$reviewer['trans_time'];

        $dayDiff = $now->diffInDays($transDateTime);
        if ($dayDiff >= 7) {
            $reviewer['errors'] .= '<li>Too old</li>';
        }

        $isDuplicate = $this->resolveDuplicateUsers($this->reviewer);
        if ($isDuplicate) {
            $reviewer['errors'] .= '<li>Duplicate</li>';
        }

        $validator = Validator::make($reviewer, [
            'trans_type'    => 'required|in:sales,service',
            'trans_date'    => 'required|date_format:"Y-m-d"',
            'trans_time'    => 'required|date_format:"H:i:s"',
            'cust_num'      => 'required|integer',
            'cust_fname'    => 'required|string',
            'cust_email'    => 'required_without:cust_phone|email',
            'cust_phone'    => 'required_without:cust_email|phone',
        ]);

        if ($validator->fails()) {
            $errors = implode(
                '',
                $validator->errors()->all('<li>:message</li>')
            );
            $reviewer['errors'] .= $errors;
        }
    }

    /**
     * Set that invite fields on the reviewer
     *
     * @param array $reviewer
     * @return void
     */
    private function resolveInviteStatus(&$reviewer)
    {
        if (isset($reviewer['cust_phone'])) {
            $reviewer['invite_method']  = 'phone';
        } else {
            $reviewer['invite_method']  = 'email';
        }

        $reviewer['invite_sent'] = true;
        $reviewer['invite_type'] = $reviewer['trans_type'];
    }

    /**
     * Determine if reviewer is duplicate
     *
     * @param array $reviewer
     * @return void
     */
    private function resolveDuplicateUsers($reviewer)
    {
        // find the duplicates
        $sameReviewer = array_filter(
            $this->reviewers,
            function ($user) use ($reviewer) {
                if ($user['cust_num'] === $reviewer['cust_num']) {
                    return true;
                }
            }
        );

        // sort duplicates by date and time
        if (count($sameReviewer) > 1) {

            usort($sameReviewer, function ($dup1, $dup2) {
                return $dup1['trans_time'] <=> $dup2['trans_time'];
            });

            usort($sameReviewer, function ($dup1, $dup2) {
                return $dup1['trans_date'] <=> $dup2['trans_date'];
            });

        }

        // reset keys
        $sameReviewer = array_values($sameReviewer);

        return ($reviewer === $sameReviewer[0]) ? false : true;
    }
}
