<?php

namespace App\Tests\functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndexGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testTermsGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/terms');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPrivacyGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/privacy');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testImprintGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/imprint');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSetlangSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lang/de');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/', $client->getResponse()->headers->get('Location'));
        $this->assertEquals('de', $client->getRequest()->getSession()->get('_locale'));
    }

    public function testSetlangFailure(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lang/illegal');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/', $client->getResponse()->headers->get('Location'));
        $this->assertEquals(null, $client->getRequest()->getSession()->get('_locale'));

    }
}