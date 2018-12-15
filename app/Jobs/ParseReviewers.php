<?php

namespace App\Jobs;

use Validator;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Events\ReviewerParsed;
use Illuminate\Http\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ParseReviewers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Contents of the csv file
     *
     * @var string
     */
    private $csvContents;

    /**
     * Create a new job instance.
     *
     * @param string $path
     * @return void
     */
    public function __construct(string $path)
    {
        $this->csvContents = Storage::get($path);
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
            $this->addFields($reviewer);
            $this->validate($reviewer);
            if (empty($reviewer['errors'])) {
                $this->resolveInviteStatus($reviewer);
            }

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
            $reviewer['errors'] = $validator->errors()->messages();
        }
    }

    private function resolveInviteStatus(&$reviewer)
    {
        $now = now()->setDate(2018, 3, 5);
        $transDateTime = $reviewer['trans_date'].' '.$reviewer['trans_time'];
        $dayDiff = $now->diffInDays($transDateTime);

        if ($dayDiff <= 7) {
            $sameReviewer = array_filter(
                $this->reviewers,
                function ($user) use ($reviewer) {
                    if ($user['cust_num'] === $reviewer['cust_num']) {
                        return true;
                    }
                }
            );

            if (count($sameReviewer) > 1) {
                usort($sameReviewer, function ($dup1, $dup2) {
                    return $dup1['trans_time'] <=> $dup2['trans_time'];
                });

                usort($sameReviewer, function ($dup1, $dup2) {
                    return $dup1['trans_date'] <=> $dup2['trans_date'];
                });

                if ($reviewer != $sameReviewer[0]) {
                    return;
                }
            }

            if (isset($reviewer['cust_phone'])) {
                $reviewer['invite_method']  = 'phone';
            } elseif (isset($reviewer['cust_email'])) {
                $reviewer['invite_method']  = 'email';
            }

            $reviewer['invite_sent'] = true;
            $reviewer['invite_type'] = $reviewer['trans_type'];
        }
    }
}
