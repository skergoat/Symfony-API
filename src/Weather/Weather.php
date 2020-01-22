<?php

namespace App\Weather;

use GuzzleHttp\Client;
use JMS\Serializer\Serializer;

class Weather
{
    private $client;
    private $serializer;
    private $apiKey;

    public function __construct(Client $client, Serializer $serializer, $apiKey)
    {
        $this->$client = $client;
        $this->$serializer = $serializer;
        $this->apiKey = $apiKey;
    }

    public function getCurrent()
    {
        $uri = "/data/2.5/weather?q=Paris&appid=" . $this->apiKey;
        $response = $this->client->get($uri);

        $data = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');

        return [
            'city' => $data['name'],
            'description' => $data['weather'][0]['main']
        ];
    
    }

}


