<?php

namespace WhatJoomlaTemplateIsThat;

class UrlBuilder
{
    private $url;
    private $templateName;
    private $templateUrl;

    public function __construct(string $url, string $templateName)
    {
        $this->url = trim($url, '/');
        $this->templateName = $templateName;
        $this->templateUrl = $this->url.'/templates/'.$this->templateName;
    }

    private function addSchema($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        return $url;
    }

    public function templateDetails() : string
    {
        return $this->addSchema($this->templateUrl.'/templateDetails.xml');
    }

    public function preview() : string
    {
        return $this->addSchema($this->templateUrl.'/template_preview.png');
    }
}
