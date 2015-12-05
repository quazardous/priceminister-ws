<?php
namespace Quazardous\PriceministerWs;

use Quazardous\PriceministerWs\Request\AbstractRequest;
use Quazardous\PriceministerWs\Request\ProductListingRequest;
use Quazardous\PriceministerWs\ApiException;
use Quazardous\PriceministerWs\CurlException;
use cURL\Request as HttpRequest;

class Client {
    protected $defaultParameters = array();
    protected $options = array(
        'timeout' => 10,
        'base_url' => 'https://ws.priceminister.com/',
        // 'base_url' => 'https://ws.sandbox.priceminister.com/',
    );
    
    /**
     * Client constructor.
     * @param array $options you can define options here (like base_url).
     * @see setOption()
     */
    public function __construct(array $options = array()) {
        $this->options = array_merge($this->options, $options);
    }
    
    /**
     * Set a given option value.
     *  - base_url : the webservice base URL (default: https://ws.priceminister.com/)
     * 
     * @param string $key
     * @param string $value
     */
    public function setOption($key, $value) {
        $this->options[$key] = $value;
    }
    
    public function getOption($key) {
        if (empty($this->options[$key])) {
            return null;
        }
        return $this->options[$key];
    }
    
    /**
     * Set a given default parameter value.
     *
     * @param string $key
     * @param string $value
     */
    public function setDefaultParameter($key, $value) {
        $this->defaultParameters[$key] = $value;
    }
    
    /**
     * Internal request function. Convert all to UTF8 if/before SimpleXMLElement parsing.
     * @param AbstractRequest $request
     * @param boolean $raw returns the raw XML string or a SimpleXMLElement object.
     * @return \SimpleXMLElement|string
     * @throws CurlException
     * @throws \RuntimeException
     * @throws ApiException
     */
    public function request(AbstractRequest $request, $raw = false) {
        $request->setParameters($this->defaultParameters, true);
        $request->validate();
        $url = $this->getOption('base_url') . $request->getPath() . '?' . http_build_query($request->getParameters());
        $http = new HttpRequest($url);
        $http->getOptions()
            ->set(CURLOPT_TIMEOUT, $this->getOption('timeout'))
            ->set(CURLOPT_RETURNTRANSFER, true)
            ->set(CURLOPT_HEADER, true);
        $response = $http->send();
        
        if ($response->hasError()) {
            $error = $response->getError();
            throw new CurlException($error ? $error->getMessage() : 'Unkown exception', $error ? $error->getCode() : null);
        }

        $content = $response->getContent();
        
        $header_size = $response->getInfo(CURLINFO_HEADER_SIZE);
        
//         $header = substr($raw, 0, $header_size);
        $body = substr($content, $header_size);
        
        $start = substr($body, 0, 256);
        
        $encoding = 'UTF-8';
        $xml_tag_size = false;
        if (!$raw) {    
            $matches = null;
            if (preg_match( '@(<\?xml[^>]+encoding="([^\s"]+)[^?]*\?>)@si', $start, $matches )) {
                $encoding = $matches[2];
                $xml_tag_size = strlen($matches[1]);
            }
        }
        
        if (preg_match( '@<\?xml[^>]+encoding="[^\s"]+[^?]*\?>\s*<errorresponse@si', $start, $matches )) {
            $xml = simplexml_load_string($body);
            if ($xml === false) {
                throw new \RuntimeException('Response content is no valid XML');
            }
            $details = array();
            if ($xml->error->details->detail) {
                foreach ($xml->error->details->detail as $detail) {
                    $details[] = (string) $detail;
                }
            }
            throw new ApiException($xml->error->message, $xml->error->type, $xml->error->code, $details);
        }
        if ($raw) return $body;
        
        if ($xml_tag_size !== false) {
            $body = '<?xml version="1.0" encoding="UTF-8"?>'.substr($body, $xml_tag_size);
        }
        
        $body = iconv($encoding, 'UTF-8//TRANSLIT', $body);
        $xml = simplexml_load_string($body);
        if ($xml === false) {
            throw new \RuntimeException('Response content is no valid XML');
        }

        return $xml;
    }
    
    /**
     * Product Listing request.
     * @param ProductListingRequest $request
     * @param boolean $raw
     * @return SimpleXMLElement|string
     */
    public function requestProductListing(ProductListingRequest $request, $raw = false) {
        return $this->request($request, $raw);
    }
}