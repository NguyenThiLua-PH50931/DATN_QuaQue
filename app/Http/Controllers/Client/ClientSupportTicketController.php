<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\admin\SupportTicket;
use App\Models\admin\SupportTicketReply;
use Illuminate\Http\Request;
use App\Mail\SupportTicketReplied;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ClientSupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())->with(['replies.admin'])->latest()->paginate(10);
        return view('frontend.support-ticket.index', compact('tickets')); // Thay đổi namespace
    }

    public function create()
    {
        return view('frontend.support-ticket.create'); // Thay đổi namespace
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'status' => 'pending',
        ]);

        // Gửi email thông báo khi tạo ticket
        if ($ticket->user && $ticket->user->email) {
            try {
                Mail::to($ticket->user->email)->send(new SupportTicketReplied($ticket));
                \Log::info('New ticket email sent to: ' . $ticket->user->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send new ticket email for ticket ' . $ticket->id . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('client.support-ticket.index')->with('success', 'Yêu cầu hỗ trợ đã được gửi!');
    }

     public function show($id)
{
    $ticket = SupportTicket::with(['replies.admin'])
        ->where('user_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    return view('frontend.support-ticket.show', compact('ticket'));
}

}
