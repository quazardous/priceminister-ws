<?php

namespace Quazardous\PriceministerWs\Request;

trait HasMultipartFileRequestTrait
{
    abstract public function addPostField($name, $data);
    
    public function setFile($filename, string $name = 'file')
    {
        $this->addPostField($name, new \CURLFile($filename));
    }

    public function setStringFile($content, $filename, string $name = 'file')
    {
        $this->addPostField($name, new \CURLStringFile($content, $filename));
    }
}
