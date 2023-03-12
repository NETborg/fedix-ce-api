<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\AuthModule\Integration;

use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;

class LogoutFlowTest extends AbstractApiTestCase
{
    public function testSuccessfulLogoutFlow(): void
    {
        $client = static::createClient();

        $this->loginUser($client);

        $client->request('GET', '/logout');

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Please sign in');
    }
}
