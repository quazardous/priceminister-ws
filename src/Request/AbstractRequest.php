<?php
namespace Quazardous\PriceministerWs\Request;

use cURL\Request as CurlRequest;
use Quazardous\PriceministerWs\ApiException;
use Quazardous\PriceministerWs\CurlException;
use Quazardous\PriceministerWs\RuntimeException;
use Quazardous\PriceministerWs\Response\BasicResponse;

abstract class AbstractRequest {
    /**
     * @var string
     */
    protected $mode = 'get';
    
    /**
     * @var string
     */
    protected $url;
    
    protected $options = [];
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
    public function getOption($key, $default = null) {
        return $this->options[$key] ?? $default;
    }
    
    protected $parameters = [];
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
    public function __construct(array $parameters = []) {
        $this->setParameters($parameters);
        $this->init();
    }
    
    protected function init()
    {
        // child stuff
    }
    
    protected $stringifyArrayParameters = true;
    /**
     * Parameters preprocessors.
     * @var array
     */
    protected $parameterProcessors = [];
    protected function preProcessParameter($key, &$value)
    {
        if ($this->stringifyArrayParameters) {
            $this->stringifyArrayParameter($value);
        }
        if (empty($this->parameterProcessors[$key])) return;
        $params = [&$value];
        call_user_func_array($this->parameterProcessors[$key], $params);
    }

    /**
     * Parameters preprocessors.
     * @var array
     */
    protected $postFieldProcessors = [];
    protected function preProcessPostField($key, &$value)
    {
        if (empty($this->postFieldProcessors[$key])) return;
        $params = [&$value];
        call_user_func_array($this->postFieldProcessors[$key], $params);
    }
    
    protected $postFields = [];
    public function addPostField($name, $data)
    {
        $this->postFields[$name] = $data;
    }
    
    /**
     * @param mixed $item
     * @return boolean
     */
    static protected function isScalarizable($item)
    {
        return 
            ( !is_array( $item ) )
            && (
                ( !is_object( $item ) && settype( $item, 'string' ) !== false )
                || ( is_object( $item ) && method_exists( $item, '__toString' ) )
            );
    }
    
    /**
     * Validate the request.
     */
    public function validate() {
        foreach ($this->parameters as $key => &$value) {
            $this->preProcessParameter($key, $value);
            if (!(self::isScalarizable($value) || is_null($value))) {
                throw new \InvalidArgumentException(sprintf("Parameter %s cannot be cast to a string", $key));
            }
            $value = (string)$value;
        }
        switch ($this->mode) {
            case 'multipart':
                foreach ($this->postFields as $key => &$value) {
                    $this->preProcessPostField($key, $value);
                    if (! $value instanceof \CURLFile) {
                        if (!(self::isScalarizable($value) || is_null($value))) {
                            throw new \InvalidArgumentException(sprintf("Post field %s cannot be cast to a string", $key));
                        }
                        $value = (string)$value;
                    }
                }
                break;
        }
    }
    
    /**
     * Create the raw CURL request.
     * @return CurlRequest
     */
    protected function getCurlRequest()
    {
        $url = $this->url . '?' . http_build_query($this->getParameters());
        $curl = new CurlRequest($url);
        $curl->getOptions()
            ->set(CURLOPT_RETURNTRANSFER, true)
            ->set(CURLOPT_HEADER, true);

        $headers = [];
        $timeout = $this->getOption('timeout');
        if ($curlOptions = $this->getOption('curl_options')) {
            foreach ((array) $curlOptions as $key => $value) {
                if (in_array($key, [CURLOPT_POSTFIELDS, CURLOPT_RETURNTRANSFER, CURLOPT_HEADER])) {
                    throw new \InvalidArgumentException(sprintf('Forbidden CURLOPT_XXX option %d', $key));
                }
                if (CURLOPT_TIMEOUT == $key) {
                    $timeout = $value;
                    continue;
                }
                if (CURLOPT_HTTPHEADER == $key) {
                    $headers = (array)$value;
                    continue;
                }
                $curl->getOptions()->set($key, $value);
            }
        }
        
        switch ($this->mode)
        {
            case 'multipart':
                // header automatique
                // $headers[] = 'Content-Type:multipart/form-data';
                $curl->getOptions()->set(CURLOPT_POSTFIELDS, (array)$this->postFields);
                break;
        }

        if (!empty($headers)) {
            $curl->getOptions()->set(CURLOPT_HTTPHEADER, $headers);
        }
        
        if ($timeout) {
            $curl->getOptions()->set(CURLOPT_TIMEOUT, $timeout);
        }
            
        return $curl;
    }
    
    /**
     * Execute the request.
     * @throws CurlException
     * @throws \Quazardous\PriceministerWs\RuntimeException
     * @throws ApiException
     * @return \Quazardous\PriceministerWs\Response\BasicResponse
     */
    public function execute() {
        $curl = $this->getCurlRequest();
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
        
        $start = substr($body, 0, 256);
        
        $matches = null;
        if (preg_match( '@<\?xml[^>]+encoding="[^\s"]+[^?]*\?>\s*<errorresponse@si', $start, $matches )) {
            $xml = simplexml_load_string($body);
            if ($xml === false) {
                throw new RuntimeException('Response content is no valid XML', RuntimeException::NO_VALID_XML);
            }
            $details = [];
            if ($xml->error->details->detail) {
                foreach ($xml->error->details->detail as $detail) {
                    $details[] = (string) $detail;
                }
            }
            throw new ApiException((string)$xml->error->message, (string)$xml->error->type, (string)$xml->error->code, $details, $basic);
        }
        
        if ($code != 200) {
            $e = new RuntimeException('HTTP code is not 200 (' . $code . ')', RuntimeException::HTTP_CODE_NOT_200);
            $e->setResponse($basic);
            throw $e;
        }
        
        return $basic;
    }
    
    /**
     * Implode arrays in comma separated string.
     * @param mixed $value
     */
    protected function stringifyArrayParameter(&$value)
    {
        if (empty($value)) return;
        if (is_scalar($value)) return;
        $value = implode(',', $value);
    }
}