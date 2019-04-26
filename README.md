# Priceminister Webservices SDK

~~Ongoing~~ Nearly done work to access Priceminister Rakuten Webservices.

https://developer.fr.shopping.rakuten.com/blog/

## Working API functions

### ProductListing
https://developer.priceminister.com/blog/en/documentation/product-data/product-listing-secure

### CategoryMap
https://developer.priceminister.com/blog/en/documentation/product-data/category-map

### GetCurrentSales
https://developer.fr.shopping.rakuten.com/blog/fr/documentation/post-confirmation-of-sale/get-current-sales/

### AcceptSale, RefuseSale
https://developer.fr.shopping.rakuten.com/blog/fr/documentation/new-sales/accept-or-refuse-sales/

### ImportStock
https://developer.fr.shopping.rakuten.com/blog/fr/documentation/inventory-management/import-csv/inventory-import/

### ImportReport
https://developer.fr.shopping.rakuten.com/blog/fr/documentation/inventory-management/import-csv/inventory-import/

## Install

```bash
composer req quazardous/priceminister-ws
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
