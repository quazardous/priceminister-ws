<?php

namespace Quazardous\PriceministerWs;

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
    
    public function __construct ($message = null, $apiType = null, $apiCode = null, $details = array()) {
        parent::__construct($message);
        $this->setApiType($apiType);
        $this->setApiCode($apiCode);
        $this->setDetails($details);
    }
    
}