<?php

namespace Quazardous\PriceministerWs;

use Quazardous\PriceministerWs\Response\BasicResponse;
/**
 * Exception thrown by the client layer.
 */
class RuntimeException extends \RuntimeException {
    // Exception code when the HTTP request was not 200
    const HTTP_CODE_NOT_200 = 1;
    // Exception code when the XML is not valid
    const NO_VALID_XML = 2;
    /**
     * 
     * @var \Quazardous\PriceministerWs\Response\BasicResponse
     */
    protected $response;
    /**
     * 
     * @param \Quazardous\PriceministerWs\Response\BasicResponse $response
     */
    public function setResponse(BasicResponse $response)
    {
        $this->reponse = $response;
    }
    
    /**
     * 
     * @return \Quazardous\PriceministerWs\Response\BasicResponse
     */
    public function getResponse()
    {
        return $this->reponse;
    }
}