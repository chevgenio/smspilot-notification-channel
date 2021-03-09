<?php

namespace Chevgenio\SmsPilot\Tests;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use Chevgenio\SmsPilot\SmsPilotApi;
use Chevgenio\SmsPilot\SmsPilotChannel;
use Chevgenio\SmsPilot\SmsPilotMessage;
use PHPUnit\Framework\TestCase;

class SmsPilotChannelTest extends TestCase
{
    /** @var SmsPilotApi|Mockery\MockInterface */
    private $client;

    /** @var SmsPilotMessage */
    private $message;

    /** @var SmsPilotChannel */
    private $channel;

    /** @var \DateTime */
    public static $sendAt;

    public function setUp(): void
    {
        $this->client = Mockery::mock(SmsPilotApi::class, [
            'apikey' => 'test',
            'sender' => 'INFORM',
        ]);
        $this->channel = new SmsPilotChannel($this->client);
        $this->message = Mockery::mock(SmsPilotMessage::class);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_can_send_a_notification(): void
    {
        $this->client->shouldReceive('send')
            ->once()
            ->with([
                'to' => '71234567890',
                'send' => 'This is my message.',
                'from' => 'INFORM',
            ]);

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_a_deferred_notification(): void
    {
        self::$sendAt = new \DateTime();

        $this->client->shouldReceive('send')
            ->once()
            ->with([
                'to' => '71234567890',
                'send' => 'This is my message.',
                'from' => 'INFORM',
                'send_datetime' => self::$sendAt->getTimestamp(),
            ]);

        $this->channel->send(new TestNotifiable(), new TestNotificationWithSendAt());
    }

    /** @test */
    public function it_does_not_send_a_message_when_to_missed(): void
    {
        $this->client->shouldNotReceive('send');

        $this->channel->send(
            new TestNotifiableWithoutRouteNotificationForSmspilot(), new TestNotification()
        );
    }

    /** @test */
    public function it_can_send_a_notification_to_multiple_phones(): void
    {
        $this->client->shouldReceive('send')
            ->once()
            ->with([
                'to'  => '71234567890,72345678901,73456789012',
                'send' => 'This is my message.',
                'from' => 'INFORM',
            ]);

        $this->channel->send(new TestNotifiableWithManyPhones(), new TestNotification());
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForSmspilot()
    {
        return '71234567890';
    }
}

class TestNotifiableWithoutRouteNotificationForSmspilot extends TestNotifiable
{
    public function routeNotificationForSmspilot()
    {
        return false;
    }
}

class TestNotifiableWithManyPhones extends TestNotifiable
{
    public function routeNotificationForSmspilot()
    {
        return ['71234567890', '72345678901', '73456789012'];
    }
}

class TestNotification extends Notification
{
    public function toSmsPilot()
    {
        return (new SmsPilotMessage)
            ->content('This is my message.')
            ->from('INFORM');
    }
}

class TestNotificationWithSendAt extends Notification
{
    public function toSmsPilot()
    {
        return (new SmsPilotMessage)
            ->content('This is my message.')
            ->from('INFORM')
            ->sendAt(SmsPilotChannelTest::$sendAt);
    }
}
