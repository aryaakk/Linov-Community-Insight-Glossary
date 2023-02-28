<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Notification;

class NotifHelper {
    private $users;
    /**
     * send notification
     */
    public function notify($class){
        return $this->users ? Notification::send($this->users, $class) : null;
    }

    public function to($users){
        $this->users = $users;
        return $this;
    }
}
