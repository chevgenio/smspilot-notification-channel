<?php

namespace Chevgenio\SmsPilot;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;
use Chevgenio\SmsPilot\Exceptions\CouldNotSendNotification;

class SmsPilotApi
{
    /** @var HttpClient */
    protected $client;

    /** @var string */
    protected $apiUrl;

    /** @var string */
    protected $apiKey;

    /** @var string */
    protected $sender;

    /** @var string */
    protected $callbackUrl;

    /** @var string */
    protected $callbackMethod;

    public function __construct(array $config)
    {
        $this->apiKey = Arr::get($config, 'apikey');
        $this->sender = Arr::get($config, 'sender');
        $this->apiUrl = Arr::get($config, 'host', 'https://smspilot.ru/api.php');
        $this->callbackUrl = Arr::get($config, 'callback');
        $this->callbackMethod = Arr::get($config, 'callback_method');

        $this->client = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }

    public function send($params)
    {
        $base = [
            'from' => $this->sender,
            'apikey' => $this->apiKey,
            'format' => 'json'
        ];

        if (!empty($this->callbackUrl)) {
            $base['callback'] = $this->callbackUrl;
            $base['callback_method'] = $this->callbackMethod;
        }

        $params = array_merge($base, array_filter($params));

        try {
            $response = $this->client->request('POST', $this->apiUrl, ['form_params' => $params]);

            $response = json_decode((string)$response->getBody(), true);

            if (isset($response['error'])) {
                throw new \DomainException($response['error']['description'], $response['error']['code']);
            }

            return $response;
        } catch (\DomainException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::serviceNotAvailable($exception);
        }
    }
}
