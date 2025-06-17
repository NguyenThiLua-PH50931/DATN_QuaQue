<?php

namespace App\Mail;

use App\Models\admin\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketReplied extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $reply;

    /**
     * Create a new message instance.
     *
     * @param SupportTicket $ticket
     * @param string|null $reply
     */
    public function __construct(SupportTicket $ticket, $reply = null)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Yêu cầu hỗ trợ #' . $this->ticket->id)
                    ->view('emails.support_ticket_replied');
    }
}
