<?php
namespace Quazardous\PriceministerWs\Request;

use cURL\Request as CurlRequest;
use Quazardous\PriceministerWs\ApiException;
use Quazardous\PriceministerWs\CurlException;
use Quazardous\PriceministerWs\RuntimeException;
use Quazardous\PriceministerWs\Response\BasicResponse;

abstract class AbstractRequest {
      
    protected $options = array();
    /**
     * Set the options.
     * @param array $options
     * @param boolean $default : the given options are default values, existing values will precede.
     */
    public function setOptions(array $options, $default = false) {
        if ($default) {
            $this->options = array_merge($options, $this->options);
        }
        else {
            $this->options = array_merge($this->options, $options);
        }
    }
    
    /**
     * Set a given option value.
     * 
     * @param string $key
     * @param mixed $value
     */
    public function setOption($key, $value) {
        $this->options[$key] = $value;
    }
    
    /**
     * Get a given option.
     * @param string $key
     * @return mixed
     */
    public function getOption($key) {
        return $this->options[$key];
    }
    
    protected $parameters = array();
    /**
     * Set the parameters.
     * @param array $parameters
     * @param boolean $default : the given parameters are default values, existing values will precede.
     */
    public function setParameters(array $parameters, $default = false) {
        if ($default) {
            $this->parameters = array_merge($parameters, $this->parameters);
        }
        else {
            $this->parameters = array_merge($this->parameters, $parameters);
        }
    }
    
    /**
     * Get the parameters
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }
    
    /**
     * Set a given parameter value.
     *
     * @param string $key
     * @param string $value
     */
    public function setParameter($key, $value) {
        $this->parameters[$key] = $value;
    }
    
    /**
     * Get a given parameter value.
     * @param string $key
     * @return string
     */
    public function getParameter($key) {
        return $this->parameters[$key];
    }
    
    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = array()) {
        $this->setParameters($parameters);
    }
    
    /**
     * Validate the parameters.
     */
    public function validate() {
        foreach ($this->parameters as $key => &$value) {
            if (!(is_scalar($value) || is_null($value))) {
                throw new \InvalidArgumentException("$key is not a scalar value");
            }
            $value = (string)$value;
        }
        
    }
    
    /**
     * Execute the request.
     * @throws CurlException
     * @throws \Quazardous\PriceministerWs\RuntimeException
     * @throws ApiException
     * @return \Quazardous\PriceministerWs\Response\BasicResponse
     */
    public function execute() {
        $url = $this->getOption('url') . '?' . http_build_query($this->getParameters());
        $curl = new CurlRequest($url);
        $curl->getOptions()
            ->set(CURLOPT_TIMEOUT, $this->getOption('timeout'))
            ->set(CURLOPT_RETURNTRANSFER, true)
            ->set(CURLOPT_HEADER, true);
        $curlResponse = $curl->send();
        
        if ($curlResponse->hasError()) {
            $error = $curlResponse->getError();
            throw new CurlException($error ? $error->getMessage() : 'Unkown exception', $error ? $error->getCode() : null);
        }
        
        $code = $curlResponse->getInfo(CURLINFO_HTTP_CODE);
        
        $content = $curlResponse->getContent();
        
        $header_size = $curlResponse->getInfo(CURLINFO_HEADER_SIZE);
        
        $header = substr($content, 0, $header_size);
        $body = substr($content, $header_size);
        $basic = new BasicResponse($header, $body);
        
        if ($code != 200) {
            $e = new RuntimeException('HTTP code is not 200 (' . $code . ')', RuntimeException::HTTP_CODE_NOT_200);
            $e->setResponse($basic);
            throw $e;
        }
        
        $start = substr($body, 0, 256);
        
        $matches = null;
        if (preg_match( '@<\?xml[^>]+encoding="[^\s"]+[^?]*\?>\s*<errorresponse@si', $start, $matches )) {
            $xml = simplexml_load_string($body);
            if ($xml === false) {
                throw new RuntimeException('Response content is no valid XML', RuntimeException::NO_VALID_XML);
            }
            $details = array();
            if ($xml->error->details->detail) {
                foreach ($xml->error->details->detail as $detail) {
                    $details[] = (string) $detail;
                }
            }
            throw new ApiException($xml->error->message, $xml->error->type, $xml->error->code, $details, $basic);
        }
        return $basic;
    }
}