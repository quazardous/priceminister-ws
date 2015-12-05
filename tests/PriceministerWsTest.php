<?php

use Quazardous\PriceministerWs\Client;
use Quazardous\PriceministerWs\Request\ProductListingRequest;

class PriceministerWsTest extends PHPUnit_Framework_TestCase
{
    public function testClient()
    {
        $client = new Client();
        $this->assertInstanceOf('Quazardous\\PriceministerWs\\Client', $client);
        return $client;
    }

    /**
     * @depends testClient
     */
    public function testClientDefaultOptionBaseUrl(Client $client)
    {
        $this->assertEquals('https://ws.priceminister.com/', $client->getOption('base_url'));
    }

    /**
     * @depends testClient
     */
    public function testClientSetOption(Client $client)
    {
        $v = rand();
        $client->setOption('test', $v);
        $this->assertEquals($v, $client->getOption('test'));
    }
    
    /**
     * @depends testClient
     * @expectedException           InvalidArgumentException
     * @expectedExceptionMessage    Missing mandatory parameter
     */
    public function testClientBadRequestMissingParameter(Client $client)
    {
        $request = new ProductListingRequest();
        $this->assertInstanceOf('Quazardous\\PriceministerWs\\Request\\ProductListingRequest', $request);
        $client->request($request);
    }
    
    /**
     * @depends testClient
     * @expectedException           Quazardous\PriceministerWs\ApiException
     * @expectedExceptionMessage    Unknown user or password.
     */
    public function testClientBadRequestBadParameterPassword(Client $client)
    {
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD . 'not_good');
        $request->setParameter('version', PRICEMINISTER_PRODUCT_LISTING_VERSION);
        $client->request($request);
    }

    /**
     * @depends testClient
     */
    public function testClientBadRequestBadParameter(Client $client)
    {
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD);
        $request->setParameter('version', PRICEMINISTER_PRODUCT_LISTING_VERSION);
        try {
            $client->request($request);
            $this->assertTrue(false);
        }
        catch(Quazardous\PriceministerWs\ApiException $e) {
            $this->assertEquals('Problem with parameters.', $e->getMessage());
            $this->assertEquals('Sender', $e->getApiType());
            $this->assertEquals('ParameterError', $e->getApiCode());
            $this->assertContains("Either 'kw', 'refs', 'productids' or 'nav' has to be present", $e->getDetails());
        }
    }    
    
    /**
     * @depends testClient
     */
    public function testClientGoodRequestHarryPotter(Client $client)
    {
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD);
        $request->setParameter('version', PRICEMINISTER_PRODUCT_LISTING_VERSION);
        $request->setParameter('refs', 9780747595823);
        $xml = $client->request($request, true);
        $this->assertContains('Harry Potter And The Deathly Hallows', $xml);
    }
    
    /**
     * @depends testClient
     */
    public function testClientGoodRequestXmlUtf8(Client $client)
    {
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD);
        $request->setParameter('version', PRICEMINISTER_PRODUCT_LISTING_VERSION);
        $request->setParameter('refs', 9780747595823);
        $xml = $client->request($request);
        $this->assertInstanceOf('SimpleXMLElement', $xml);
        $string = $xml->asXML();
        $this->assertRegExp('@<\?xml[^>]+encoding="UTF-8"[^?]*\?>@si', substr($string, 0, 128));
        $this->assertContains('Harry Potter And The Deathly Hallows', $string);
        $this->assertContains('Livres en langue étrangère', $string);
    }
    
    /**
     * @depends testClient
     */
    public function testClientPassDefaultParameters(Client $client)
    {
        $client->setDefaultParameter('login', PRICEMINISTER_LOGIN);
        $client->setDefaultParameter('pwd', PRICEMINISTER_PWD);
        
        $request = new ProductListingRequest();
        $request->setParameter('kw', 'azerty');
        $request->setParameter('version', PRICEMINISTER_PRODUCT_LISTING_VERSION);
        $client->request($request);
    }
    
    /**
     * MUST BE LAST
     * @depends testClient
     * @expectedException           Quazardous\PriceministerWs\CurlException
     * @expectedExceptionMessage    Could not resolve host: __not_existing_domain__.not
     */
    public function testClientBadRequestBadUrl(Client $client)
    {
        $client->setOption('base_url', 'https://__not_existing_domain__.not/');
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD . 'not_good');
        $request->setParameter('version', PRICEMINISTER_PRODUCT_LISTING_VERSION);
        $client->request($request);
    }

}