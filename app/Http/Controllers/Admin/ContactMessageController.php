<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    public function index()
    {
        $query = ContactMessage::latest();

        if (request('filter') === 'unread') {
            $query->unread();
        }

        return Inertia::render('Admin/Contact/Index', [
            'messages' => $query->paginate(10)->withQueryString(),
            'filters' => request()->only(['filter']),
            'stats' => [
                'total' => ContactMessage::count(),
                'unread' => ContactMessage::unread()->count(),
            ],
        ]);
    }

    public function show(ContactMessage $pesan)
    {
        // Mark as read when viewed
        if (!$pesan->is_read) {
            $pesan->update(['is_read' => true]);
        }

        return Inertia::render('Admin/Contact/Show', [
            'message' => $pesan,
        ]);
    }

    public function destroy(ContactMessage $pesan)
    {
        $pesan->delete();

        return redirect()->route('admin.pesan.index')->with('success', 'Pesan berhasil dihapus.');
    }

    public function markAsRead(ContactMessage $pesan)
    {
        $pesan->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Pesan ditandai sudah dibaca.');
    }
}
