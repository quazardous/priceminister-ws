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
        'url' => 'https://ws.priceminister.com/listing_ssl_ws',
    );
    
    protected $parameters = array(
        'action' => 'listing',
        'version' => '2015-07-05',
//         'login' => null,
//         'pwd' => null,
//         'version' => null,
//         'scope' => null,
//         'kw' => null,
//         'nav' => null,
//         'refs' => null,
//         'productids' => null,
//         'nbproductsperpage' => null,
//         'pagenumber' => null,
    );
    
    public function validate() {
        if((!empty($this->parameters['refs'])) && is_array($this->parameters['refs'])) {
            $this->parameters['refs'] = implode(',', $this->parameters['refs']);
        }
        if((!empty($this->parameters['productids'])) && is_array($this->parameters['productids'])) {
            $this->parameters['productids'] = implode(',', $this->parameters['productids']);
        }
        if (empty($this->parameters['login'])
            ||empty($this->parameters['pwd'])
            ||empty($this->parameters['version']))
        {
            throw new \InvalidArgumentException("Missing mandatory parameter");
        }
        parent::validate();
    }
}