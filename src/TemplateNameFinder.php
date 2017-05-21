<?php

namespace WhatJoomlaTemplateIsThat;

class TemplateNameFinder
{
    private $pageContent;

    public function __construct(string $pageContent)
    {
        $this->pageContent = $pageContent;
    }

    public function getTemplateName()
    {
        preg_match('/\/templates\/([^\/]*)\//', $this->pageContent, $match);

        return $match[1];
    }
}
