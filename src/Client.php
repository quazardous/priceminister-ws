<?php
namespace Quazardous\PriceministerWs;

use Quazardous\PriceministerWs\Request\AbstractRequest;
use cURL\Request as CurlRequest;

class Client {
    
    protected $defaultParameters = [];
    protected $defaultOptions = [
        'timeout' => 10,
    ];
    
    /**
     * Client constructor.
     * @param array $options you can define default options here (like timeout).
     * @see setOption()
     */
    public function __construct(array $options = []) {
        $this->defaultOptions = array_merge($this->defaultOptions, $options);
    }
    
    /**
     * Set a given option value.
     * @param string $key
     * @param string $value
     */
    public function setDefaultOption($key, $value) {
        $this->defaultOptions[$key] = $value;
    }
    
    /**
     * Get a given option value.
     * @param string $key
     * @return string
     */
    public function getDefaultOption($key) {
        return $this->defaultOptions[$key];
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
     * Get a given parameter value.
     * @param string $key
     * @return string
     */
    public function getDefaultParameter($key) {
        return $this->defaultParameters[$key];
    }
    
    /**
     * Run the request.
     * @param AbstractRequest $request
     * @throws CurlException
     * @return \Quazardous\PriceministerWs\Response\BasicResponse
     */
    public function request(AbstractRequest $request) {
        $request->setOptions($this->defaultOptions, true);
        $request->setParameters($this->defaultParameters, true);
        $request->validate();

        $curl = $this->getCurlRequest($request);
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
        return $request->createResponse($code, $header, $body);
    }
    
    /**
     * Create the raw CURL request.
     * @return CurlRequest
     */
    protected function getCurlRequest(AbstractRequest $request)
    {
        $url = $request->getUrl() . '?' . http_build_query($request->getParameters());
        $curl = new CurlRequest($url);
        $curl->getOptions()
            ->set(CURLOPT_RETURNTRANSFER, true)
            ->set(CURLOPT_HEADER, true);
        
        $headers = [];
        $timeout = $request->getOption('timeout');
        if ($curlOptions = $request->getOption('curl_options')) {
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
        
        switch ($request->getMode())
        {
            case 'multipart':
                // header automatique
                // $headers[] = 'Content-Type:multipart/form-data';
                $curl->getOptions()->set(CURLOPT_POSTFIELDS, (array)$request->getPostFields());
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

}