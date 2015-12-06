<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * Product Listing (legacy) request class.
 * 
 * @link https://developer.priceminister.com/blog/en/documentation/product-data/product-listing-secure
 *
 */
class ProductListingLegacyRequest extends AbstractRequest {
    protected $options = array(
        'url' => 'http://ws.priceminister.com/listing_ws',
    );
    
    protected $parameters = array(
        'action' => 'listing',
        'version' => '2014-11-04',
    );
    
    public function validate() {
        if((!empty($this->parameters['refs'])) && is_array($this->parameters['refs'])) {
            $this->parameters['refs'] = implode(',', $this->parameters['refs']);
        }
        if((!empty($this->parameters['productids'])) && is_array($this->parameters['productids'])) {
            $this->parameters['productids'] = implode(',', $this->parameters['productids']);
        }
        parent::validate();
    }
}