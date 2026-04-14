<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Marca TODAS como leídas
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }

    // Marca UNA específica como leída y te lleva al enlace
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Si la notificación trae una URL, te lleva allá. Si no, te deja donde estabas.
        return redirect($notification->data['url'] ?? url()->previous());
    }
}
