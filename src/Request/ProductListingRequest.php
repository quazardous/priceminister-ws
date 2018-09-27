<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * Product Listing request class.
 * 
 * @link https://developer.priceminister.com/blog/en/documentation/product-data/product-listing-secure
 *
 */
class ProductListingRequest extends AbstractRequest {
    protected $options = array(
        'url' => 'https://ws.fr.shopping.rakuten.com/listing_ssl_ws',
    );
    
    protected $parameters = array(
        'action' => 'listing',
        'version' => '2015-07-05',
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