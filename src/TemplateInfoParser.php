<?php

namespace WhatJoomlaTemplateIsThat;

use GuzzleHttp\Client;
use SimpleXMLElement;

class TemplateInfoParser
{
    private $client;
    private $templateDetailsUrl;
    private $templateDetailsContent;
    private $templateDetails;

    public function __construct(Client $client, $templateDetailsUrl)
    {
        $this->client = $client;
        $this->templateDetailsUrl = $templateDetailsUrl;

        $this->templateDetailsContent = $this->getTemplateDetails();
        $this->templateDetails = new SimpleXMLElement($this->templateDetailsContent);
    }

    public function getTemplateDetails() : string
    {
        try {
            $response = $this->client->request('GET', $this->templateDetailsUrl);
        } catch (Exception $e) {
            throw new RemotePageErrorException('Error Processing Request', $response->getStatusCode());
        }

        $body = $response->getBody();

        return $body;
    }

    public function getName() : string
    {
        return $this->templateDetails->name;
    }

    public function getAuthor() : string
    {
        return $this->templateDetails->author;
    }

    public function getAuthorHomepage() : string
    {
        return $this->templateDetails->authorUrl;
    }

    public function getAuthorEmail() : string
    {
        return $this->templateDetails->authorEmail;
    }

    public function getDescription() : string
    {
        return $this->templateDetails->description;
    }

    public function getVersion() : string
    {
        return $this->templateDetails->version;
    }

    public function getCreationDate() : string
    {
        return $this->templateDetails->creationDate;
    }

    public function getCopyright() : string
    {
        return $this->templateDetails->copyright;
    }
}
