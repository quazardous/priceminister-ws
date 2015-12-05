# Priceminister Webservices SDK

Ongoing work to access Priceminister Webservices.

https://developer.priceminister.com

## Working API functions
### Product Listing
https://developer.priceminister.com/blog/en/documentation/product-data/product-listing-secure

## Install

```json
...
require : {
    "quazardous/priceminister-ws" : "*@dev",
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
$request->setParameter('version', '2015-07-05');
$request->setParameter('refs', 9780747595823);
$xml = $client->request($request);
```