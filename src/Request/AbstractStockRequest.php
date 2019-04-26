<?php
namespace Quazardous\PriceministerWs\Request;

abstract class AbstractStockRequest extends AbstractRequest {
    protected $url = 'https://ws.fr.shopping.rakuten.com/stock_ws';
}