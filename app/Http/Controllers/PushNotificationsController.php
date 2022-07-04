<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class PushNotificationsController extends Controller
{
    /**
     * Store the PushSubscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSubscription(Request $request){
        $this->validate($request,[
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = $request->user();
        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true],200);
    }

    /**
     * Send Push Notifications to all users.
     *
     */
    public function push(){
        Notification::send(User::all(),new PushNotification());
        return response()->json(['success' => true],200);
    }
}
