<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class ResendDocumentsNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
            FcmChannel::class,
        ];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
        ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
            ->setTitle('تنبية')
            ->setBody('يرجى أعادت ارسال الأوراق الثبوتية'));
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'تنبية',
            'body' => 'يرجى أعادت ارسال الأوراق الثبوتية',
        ];
    }
}
