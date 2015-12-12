<?php

namespace Quazardous\PriceministerWs;
use Quazardous\PriceministerWs\Response\BasicResponse;

/**
 * Exception thrown by Priceminister API layer.
 */
class ApiException extends \RuntimeException {
    
    protected $apiType;
    public function setApiType($apiType) {
        $this->apiType = $apiType;
    }
    public function getApiType() {
        return $this->apiType;
    }
    
    protected $apiCode;
    public function setApiCode($apiCode) {
        $this->apiCode = $apiCode;
    }
    public function getApiCode() {
        return $this->apiCode;
    }
    
    protected $details = array();
    public function setDetails($details) {
        $this->details = $details;
    }
    public function getDetails() {
        return $this->details;
    }
    
    protected $response;
    public function setResponse($response) {
        $this->response = $response;
    }
    /**
     * @return \Quazardous\PriceministerWs\Response\BasicResponse
     */
    public function getResponse() {
        return $this->response;
    }
    
    public function __construct ($message = null, $apiType = null, $apiCode = null, $details = array(), BasicResponse $response = null) {
        parent::__construct($message);
        $this->setApiType($apiType);
        $this->setApiCode($apiCode);
        $this->setDetails($details);
        $this->setResponse($response);
    }
    
}