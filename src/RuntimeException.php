<?php

namespace Quazardous\PriceministerWs;

/**
 * Exception thrown by the client layer.
 */
class RuntimeException extends \RuntimeException {
    // Exception code when the HTTP request was not 200
    const HTTP_CODE_NOT_200 = 1;
    // Exception code when the XML is not valid
    const NO_VALID_XML = 2;
}