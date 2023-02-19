<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class Telegram extends Notification
{
    private $content;

    private $image;

    private $buttons;

    public function __construct($content, $image = null, $buttons = [])
    {
        $this->content = $content;
        $this->image = $image;
        $this->buttons = $buttons;
    }

    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        $msg = TelegramFile::create();
        $msg->to($notifiable->telegram_user_id);
        $msg->content($this->content);
        $msg->file($this->image, 'photo');
        $msg->disableNotification(false);

        foreach ($this->buttons as $button) {
            $msg->button($button['caption'], $button['url']);
        }

        return $msg;
    }
}
