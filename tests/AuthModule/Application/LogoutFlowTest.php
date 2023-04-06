<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\AuthModule\Application;

use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;

class LogoutFlowTest extends AbstractApiTestCase
{
    public function testSuccessfulLogoutFlow(): void
    {
        $client = static::createClient();

        $this->loginUser(client: $client, firewall: self::FIREWALL_MAIN);

        $client->request('GET', '/logout');

        $this->assertResponseRedirects('https://fedx.social');
    }
}
