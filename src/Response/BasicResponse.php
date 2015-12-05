<?php

namespace Quazardous\PriceministerWs\Response;

class BasicResponse {
    protected $rawBody = null;
    protected $rawHeaders = null;
    
    public function __construct($rawHeaders, $rawBody) {
        $this->rawHeaders = $rawHeaders;
        $this->rawBody = $rawBody;
    }
    
    public function getRawHeaders() {
        return  $this->rawHeaders;
    }
    
    public function getRawBody() {
        return  $this->rawBody;
    }
    
    protected $xml = null;
    /**
     * Return the body as SimpleXMLElement.
     * @throws \RuntimeException
     * @return SimpleXMLElement
     */
    public function getBodyAsSimpleXmlElement() {
        if (!$this->xml) {
        
            $body = $this->rawBody;
            $start = substr($body, 0, 256);
            
            $encoding = 'UTF-8';
            $xml_tag_size = false;
            $matches = null;
            if (preg_match( '@(<\?xml[^>]+encoding="([^\s"]+)[^?]*\?>)@si', $start, $matches )) {
                $encoding = $matches[2];
                $xml_tag_size = strlen($matches[1]);
            }
            
            if ($xml_tag_size !== false) {
                $body = '<?xml version="1.0" encoding="UTF-8"?>'.substr($body, $xml_tag_size);
            }
            
            $body = iconv($encoding, 'UTF-8//TRANSLIT', $body);
            $xml = simplexml_load_string($body);
            if ($xml === false) {
                throw new \RuntimeException('Response content is no valid XML');
            }
            
            $this->xml = $xml;
        }
        return  $this->xml;
    }
}