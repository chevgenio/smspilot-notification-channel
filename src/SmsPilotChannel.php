<?php

namespace Chevgenio\SmsPilot;

use Illuminate\Notifications\Notification;
use Chevgenio\SmsPilot\Exceptions\CouldNotSendNotification;

class SmsPilotChannel
{
    /** @var SmsPilotApi */
    protected $client;

    public function __construct(SmsPilotApi $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return array|null
     * @throws CouldNotSendNotification
     *
     */
    public function send($notifiable, Notification $notification): ?array
    {
        if (!$to = $notifiable->routeNotificationFor('smspilot', $notification)) {
            return null;
        }

        $to = is_array($to) ? $to : [$to];

        $message = $notification->toSmsPilot($notifiable);

        if (is_string($message)) {
            $message = new SmsPilotMessage($message);
        }

        $payload = [
            'to' => implode(',', $to),
            'send' => $message->content,
            'from' => $message->from,
        ];

        if ($message->sendAt instanceof \DateTimeInterface) {
            $payload['send_datetime'] = $message->sendAt->getTimestamp();
        }

        return $this->client->send($payload);
    }
}
