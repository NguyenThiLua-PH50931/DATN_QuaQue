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

    public function __construct(SupportTicket $ticket, $reply)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
    }

    public function build()
    {
        return $this->subject('Phản hồi yêu cầu hỗ trợ #' . $this->ticket->id)
                    ->view('emails.support_ticket_replied')
                    ->with([
                        'ticket' => $this->ticket,
                        'reply' => $this->reply,
                    ]);
    }
}
