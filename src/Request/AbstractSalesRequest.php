<?php
namespace Quazardous\PriceministerWs\Request;

abstract class AbstractSalesRequest extends AbstractRequest {
    protected $url = 'https://ws.fr.shopping.rakuten.com/sales_ws';
}