<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExceptionOccured extends Mailable
{
    use Queueable, SerializesModels;
    protected $exceptionHtml;
    /**
     * Create a new message instance.
     *
     * @param $exceptionHtml
     * @internal param Exception $e
     */
    public function __construct($exceptionHtml)
    {
        $this->exceptionHtml = $exceptionHtml;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Exception occured !')
            ->view('emails.exception')
            ->with('exceptionHtml', $this->exceptionHtml);
    }
}