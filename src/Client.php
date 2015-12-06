<?php
namespace Quazardous\PriceministerWs;

use Quazardous\PriceministerWs\Request\AbstractRequest;

class Client {
    protected $defaultParameters = array();
    protected $defaultOptions = array(
        'timeout' => 10,
    );
    
    /**
     * Client constructor.
     * @param array $options you can define default options here (like timeout).
     * @see setOption()
     */
    public function __construct(array $options = array()) {
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
     * Internal request function. Convert all to UTF8 if/before SimpleXMLElement parsing.
     * @param Request\AbstractRequest $request
     * @return Response\BasicResponse
     * @throws CurlException
     * @throws \RuntimeException
     * @throws ApiException
     */
    public function request(AbstractRequest $request) {
        $request->setOptions($this->defaultOptions, true);
        $request->setParameters($this->defaultParameters, true);
        $request->validate();
        return $request->execute();
    }

}