<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\SupportTicket;
use App\Models\admin\SupportTicketReply;
use Illuminate\Http\Request;
use App\Mail\SupportTicketReplied;
use Illuminate\Support\Facades\Mail;

class SupportTicketController extends Controller
{


    public function index(Request $request)
    {
        $query = SupportTicket::with(['user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $tickets = $query->latest()->paginate(10);
        $tickets->appends($request->all());

        return view('backend.support-ticket.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'replies.admin'])->findOrFail($id);
        return view('backend.support-ticket.show', compact('ticket'));
    }

    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'reply' => ['required', 'string', 'max:2000'],
        ]);

        $ticket = SupportTicket::findOrFail($id);

        // Tạo phản hồi
        SupportTicketReply::create([
            'support_ticket_id' => $id,
            'admin_id' => auth()->id(),
            'reply' => $request->reply,
        ]);

        // Cập nhật trạng thái thành resolved
        $ticket->update(['status' => 'resolved']);

        // Gửi email thông báo
        try {
            Mail::to($ticket->user->email)->send(new SupportTicketReplied($ticket, $request->reply));
        } catch (\Exception $e) {
            return redirect()->route('admin.support-ticket.show', $id)->with('error', 'Phản hồi đã được gửi nhưng không thể gửi email!');
        }

        return redirect()->route('admin.support-ticket.show', $id)->with('success', 'Phản hồi đã được gửi và trạng thái được cập nhật!');
    }

    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('admin.support-ticket.index')->with('success', 'Yêu cầu hỗ trợ đã được xóa!');
    }
    public function create()
    {
        return view('frontend.support-ticket.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
        ]);

        SupportTicket::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'status' => 'pending',
        ]);

        return redirect()->route('client.home')->with('success', 'Yêu cầu hỗ trợ đã được gửi!');
    }
}
