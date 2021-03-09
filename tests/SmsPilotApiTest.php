<?php

namespace Chevgenio\SmsPilot\Tests;

use Chevgenio\SmsPilot\SmsPilotApi;
use PHPUnit\Framework\TestCase;

class SmsPilotApiTest extends TestCase
{
    /** @test */
    public function it_has_config_with_default_api_url(): void
    {
        $smspilot = $this->getExtendedSmsPilotApi([
            'apikey' => $apiKey = 'test',
            'sender' => $sender = 'sender',
        ]);

        $this->assertEquals($apiKey, $smspilot->getApiKey());
        $this->assertEquals($sender, $smspilot->getSender());
        $this->assertEquals('https://smspilot.ru/api.php', $smspilot->getApiUrl());
    }

    private function getExtendedSmsPilotApi(array $config)
    {
        return new class($config) extends SmsPilotApi {
            public function getApiUrl(): string
            {
                return $this->apiUrl;
            }

            public function getApiKey(): string
            {
                return $this->apiKey;
            }

            public function getSender(): string
            {
                return $this->sender;
            }
        };
    }
}
