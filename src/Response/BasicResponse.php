<?php

namespace Quazardous\PriceministerWs\Response;

use Quazardous\PriceministerWs\RuntimeException;

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
    
    protected $sanitizeBodyCallback;
    public function setSanitizeBodyCallback(callable $callback)
    {
        $this->sanitizeBodyCallback = $callback;
    }
    
    protected $sanitizedBody = null;
    /**
     * Return a sanitized body.
     * @return string
     */
    public function getSanitizedBody()
    {
        if (is_null($this->sanitizedBody)) {
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
            if ($this->sanitizeBodyCallback) {
                $body = call_user_func($this->sanitizeBodyCallback, $body);
            }
            $this->sanitizedBody = $body;
        }
        return $this->sanitizedBody;
    }
    
    protected $xml = null;
    /**
     * Return the body as SimpleXMLElement.
     * @throws \Quazardous\PriceministerWs\RuntimeException
     * @return \SimpleXMLElement
     */
    public function getBodyAsSimpleXmlElement() {
        if (!$this->xml) {
            $xml = simplexml_load_string($this->getSanitizedBody(), \SimpleXMLElement::class, LIBXML_NOCDATA);
            if ($xml === false) {
                throw new RuntimeException('Response content is no valid XML', RuntimeException::NO_VALID_XML);
            }
            $this->xml = $xml;
        }
        return  $this->xml;
    }
    
    protected $array = null;
    public function getBodyAsArray()
    {
        if (is_null($this->array)) {
            $this->array = @json_decode(@json_encode((array)$this->getBodyAsSimpleXmlElement()), true);
        }
        return $this->array;
    }
    
    protected $headers = null;
    /**
     * Return the headers as associative array or return the given header.
     * @param string $key
     * @return string|string[]
     */
    public function getHeaders($key = null) {
        if (!$this->headers) {
            $this->headers = http_parse_headers ( $this->rawHeaders );
        }
        if ($key) {
            if (isset($this->headers[$key])) {
                return $this->headers[$key];
            }
        }
        return $this->headers;
    }
}