<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CostumDBUpsertChannel 
{

  public function send($notifiable, Notification $notification)
  {
    $param = $notification->toDatabase($notifiable);

    return $notifiable->routeNotificationFor('database')->updateOrCreate(
    [
      'type' => get_class($notification),
      'notifiable_type'=>get_class($notifiable),
      'notifiable_id'=>$notifiable->id,
      'detail_id'=>@$param['detail_id'],
      'created_by'=>auth()->id()
    ],
    [
        'id' => $notification->id,
        'type' => get_class($notification),
        'message_title'=>@$param['message_title'],
        'message' => @$param['message'],
        'icon' => @$param['icon'],
        'detail_id'=>@$param['detail_id'],
        'path' => @$param['path'],
        'data' => @$param['data'],
        'read_at' => null,
        'created_by'=>auth()->id()
    ]);
  }

}