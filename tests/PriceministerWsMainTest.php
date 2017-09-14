<?php

use Quazardous\PriceministerWs\Client;
use Quazardous\PriceministerWs\Request\ProductListingRequest;
use Quazardous\PriceministerWs\Request\ProductListingLegacyRequest;
use Quazardous\PriceministerWs\Request\CategoryMapRequest;
use PHPUnit\Framework\TestCase;

class PriceministerWsMainTest extends TestCase
{
    public function testClient()
    {
        $client = new Client();
        $this->assertInstanceOf('Quazardous\\PriceministerWs\\Client', $client);
        return $client;
    }

    /**
     * @depends testClient
     * @expectedException           Quazardous\PriceministerWs\RuntimeException
     * @expectedExceptionCode       1
     */
    public function testClientBadRequestLegacyNotFound(Client $client)
    {
        $request = new ProductListingLegacyRequest();
        $request->setOption('url', 'http://ws.priceminister.com/starwars404');
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('refs', 9780747595823);
        $client->request($request);
    }
    
    /**
     * @depends testClient
     */
    public function testClientDefaultOptionTimeout(Client $client)
    {
        $this->assertEquals(10, $client->getDefaultOption('timeout'));
    }

    /**
     * @depends testClient
     */
    public function testClientSetDefaultOption(Client $client)
    {
        $v = rand();
        $client->setDefaultOption('test', $v);
        $this->assertEquals($v, $client->getDefaultOption('test'));
    }
    
    /**
     * @depends testClient
     * @expectedException           Quazardous\PriceministerWs\ApiException
     * @expectedExceptionMessage    Problem with parameters
     */
    public function testClientBadRequestMissingParameter(Client $client)
    {
        $request = new ProductListingRequest();
        $client->request($request);
    }
    
    /**
     * @depends testClient
     * @expectedException           InvalidArgumentException
     * @expectedExceptionMessage    pwd is not a scalar value
     */
    public function testClientBadRequestNonScalarPassword(Client $client)
    {
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', array(PRICEMINISTER_PWD . 'not_good'));
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
        $client->request($request);
    }

    /**
     * @depends testClient
     */
    public function testClientBadRequestCategoryBadParameterLogin(Client $client)
    {
        $request = new CategoryMapRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN . '_oops');
        $response = $client->request($request);
        $this->assertInstanceOf('Quazardous\PriceministerWs\Response\BasicResponse', $response);
        $this->assertContains('Art-Collection_Buvard', $response->getRawBody());
        // it's working...
    }
    
    /**
     * @depends testClient
     */
    public function testClientBadRequestCategoryNoParameterLogin(Client $client)
    {
        $request = new CategoryMapRequest();
        $response = $client->request($request);
        $this->assertInstanceOf('Quazardous\PriceministerWs\Response\BasicResponse', $response);
        $this->assertContains('Art-Collection_Buvard', $response->getRawBody());
        // it's working...
    }
    
    /**
     * @depends testClient
     */
    public function testClientGoodRequestCategory(Client $client)
    {
        $request = new CategoryMapRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $response = $client->request($request);
        $this->assertInstanceOf('Quazardous\PriceministerWs\Response\BasicResponse', $response);
        $this->assertContains('Art-Collection_Buvard', $response->getRawBody());
        // it's working...
    }

    /**
     * @depends testClient
     */
    public function testClientBadRequestBadParameter(Client $client)
    {
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD);
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
        $request->setParameter('refs', 9780747595823);
        $response = $client->request($request);
        $this->assertInstanceOf('Quazardous\PriceministerWs\Response\BasicResponse', $response);
        $this->assertContains('Harry Potter And The Deathly Hallows', $response->getRawBody());
    }
    
    /**
     * @depends testClient
     */
    public function testClientGoodRequestArrayOfRefs(Client $client)
    {
        $refs = array(9780747595823, 9780552167239);
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD);
        $request->setParameter('refs', $refs);
        $response = $client->request($request);
        $this->assertEquals(implode(',', $refs), $request->getParameter('refs'));
        $this->assertInstanceOf('Quazardous\PriceministerWs\Response\BasicResponse', $response);
        $this->assertContains('Harry Potter And The Deathly Hallows', $response->getRawBody());
        $this->assertContains('The Long Earth', $response->getRawBody());
    }
    
    /**
     * @depends testClient
     */
    public function testClientGoodRequestXmlUtf8(Client $client)
    {
        $request = new ProductListingRequest();
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD);
        $request->setParameter('refs', 9780747595823);
        $response = $client->request($request);
        $this->assertInstanceOf('Quazardous\PriceministerWs\Response\BasicResponse', $response);
        $xml = $response->getBodyAsSimpleXmlElement();
        $this->assertInstanceOf('SimpleXMLElement', $xml);
        $string = $xml->asXML();
        $this->assertRegExp('@<\?xml[^>]+encoding="UTF-8"[^?]*\?>@si', substr($string, 0, 128));
        $this->assertContains('Harry Potter And The Deathly Hallows', $string);
        $this->assertContains('Livres en langue étrangère', $string);
    }

    /**
     * @depends testClient
     */
    public function testClientHeaders(Client $client)
    {
        $client->setDefaultParameter('login', PRICEMINISTER_LOGIN);
        $client->setDefaultParameter('pwd', PRICEMINISTER_PWD);
    
        $request = new ProductListingRequest();
        $request->setParameter('kw', 'azerty');
        $response = $client->request($request);
        $this->assertContains('Content-Type: text/xml;charset=ISO-8859-1', $response->getRawHeaders());
        $this->assertEquals('text/xml;charset=ISO-8859-1', $response->getHeaders('Content-Type'));
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
        $client->request($request);
        
        $this->assertTrue(true);
    }
    
    /**
     * @depends testClient
     * @expectedException           Quazardous\PriceministerWs\CurlException
     * @expectedExceptionMessage    Could not resolve host: __not_existing_domain__.not
     */
    public function testClientBadRequestBadUrl(Client $client)
    {
        $request = new ProductListingRequest();
        $request->setOption('url', 'https://__not_existing_domain__.not/');
        $request->setParameter('login', PRICEMINISTER_LOGIN);
        $request->setParameter('pwd', PRICEMINISTER_PWD);
        $client->request($request);
    }

}