# Priceminister Webservices SDK

Ongoing work to access Priceminister Webservices.

https://developer.priceminister.com

## Working API functions
### Product Listing
https://developer.priceminister.com/blog/en/documentation/product-data/product-listing-secure

### Product Listing (legacy)
https://developer.priceminister.com/blog/en/documentation/product-data/product-listing

### Category List
https://developer.priceminister.com/blog/en/documentation/product-data/category-map

## Install

```json
...
require : {
    "quazardous/priceminister-ws" : "*@dev"
}
...
```

## Usage

```php
use Quazardous\PriceministerWs\Client;
use Quazardous\PriceministerWs\Request\ProductListingRequest;

$client = new Client();
$request = new ProductListingRequest();
$request->setParameter('login', '***');
$request->setParameter('pwd', '***');
$request->setParameter('refs', 9780747595823);
$response = $client->request($request);
```