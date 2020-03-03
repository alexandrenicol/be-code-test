<?php

namespace App\Mail;

use App\Organisation;
use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganisationCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Organisation
     */
    public $organisation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $trialEndDate = new Carbon($this->organisation->trial_end);

        return $this->from('example@example.com')
                    ->view('emails.organisation.created')
                    ->with([
                        'trialEndDate' => $trialEndDate->toFormattedDateString()
                    ]);
    }
}
