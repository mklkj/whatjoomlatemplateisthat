<?php

namespace WhatJoomlaTemplateIsThat;

use GuzzleHttp\Client;

class Connect
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getPage(string $url) : string
    {
        try {
            $response = $this->client->request('GET', $url);
        } catch (Exception $e) {
            throw new RemotePageErrorException('Error Processing Request', $response->getStatusCode());
        }

        $body = $response->getBody();

        return $body;
    }
}
