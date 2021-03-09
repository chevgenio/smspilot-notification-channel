<?php

namespace Chevgenio\SmsPilot\Test;

use Chevgenio\SmsPilot\SmsPilotMessage;
use PHPUnit\Framework\TestCase;

class SmsPilotMessageTest extends TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message(): void
    {
        $message = new SmsPilotMessage('This is my message.');

        $this->assertEquals('This is my message.', $message->content);
    }

    /** @test */
    public function it_can_set_the_content(): void
    {
        $message = (new SmsPilotMessage())->content('This is my message.');

        $this->assertEquals('This is my message.', $message->content);
    }

    /** @test */
    public function it_can_set_the_from(): void
    {
        $message = (new SmsPilotMessage())->from('INFORM');

        $this->assertEquals('INFORM', $message->from);
    }

    /** @test */
    public function it_can_set_the_send_at(): void
    {
        $message = (new SmsPilotMessage())->sendAt($sendAt = \date_create());

        $this->assertEquals($sendAt, $message->sendAt);
    }
}
