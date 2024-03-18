<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        return $request->user()->notifications;
    }

    public function getUnreadNotifications(Request $request)
    {
        return $request->user()->unreadNotifications;
    }

    public function markAsRead(Request $request, $id)
    {
        $request->user()->notifications()->where('id', $id)->first()->markAsRead();
        return response()->json(['message' => 'Notification marked as read']);
    }


    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    }
}
