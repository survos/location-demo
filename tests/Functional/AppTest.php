<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Location Demo');
    }

    public function testAddArticleWithLocations(): void
    {
+        $client = static::createClient();
        $crawler = $client->request('GET', '/');


    }
}
