<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\Integration\Sanitiser;

use Netborg\Fediverse\Api\Sanitiser\UserUsernameSanitiser;
use Netborg\Fediverse\Api\Tests\Integration\AbstractKernelTestCase;

/** @covers \Netborg\Fediverse\Api\Sanitiser\UserUsernameSanitiser */
class UserUsernameSanitiserTest extends AbstractKernelTestCase
{
    private UserUsernameSanitiser|null $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->getContainer()->get('Netborg\Fediverse\Api\Sanitiser\UserUsernameSanitiser');
    }

    protected function tearDown(): void
    {
        unset($this->service);
        $this->service = null;
        parent::tearDown();
    }

    public function testGetUsernamePrefixMethod(): void
    {
        $result = $this->service->getUsernamePrefix();

        $this->assertSame('~', $result);
    }

    public function testGetRegexPatternMethod(): void
    {
        $result = $this->service->getRegexPattern();

        $this->assertSame('/^~[a-zA-Z0-9_\.\-]+$/', $result);
    }

    public function sanitiseDataProvider(): \Generator
    {
        yield ['~my-username', 'my-username'];
        yield ['~my.username', 'my.username'];
        yield ['~My.useRname', 'My.useRname'];
        yield [' ~my-user name  ', 'my-username'];
        yield [' my!user#name@$%^&*()[]<>/<', 'myusername'];
    }

    /** @dataProvider sanitiseDataProvider */
    public function testSanitiseMethod(string $username, string $expected): void
    {
        $result = $this->service->sanitise($username);

        $this->assertSame($expected, $result);
    }

    public function prefixiseDataProvider(): \Generator
    {
        yield ['my-username', '~my-username'];
        yield ['my.username', '~my.username'];
        yield ['My.useRname', '~My.useRname'];
        yield [' my-user name  ', '~my-username'];
        yield [' my!user#name@$%^&*()[]<>/<', '~myusername'];
    }

    /** @dataProvider prefixiseDataProvider */
    public function testPrefixiseMethod(string $username, string $expected): void
    {
        $result = $this->service->prefixise($username);

        $this->assertSame($expected, $result);
    }

    public function deprefixiseDataProvider(): \Generator
    {
        yield ['~my-username', 'my-username', false];
        yield ['~my.username', 'my.username', false];
        yield ['~My.useRname', 'My.useRname', false];
        yield [' ~my-user name  ', 'my-user name', false];
        yield [' ~my!user#name@$%^&*()[]<>/<', 'my!user#name@$%^&*()[]<>/<', false];

        yield ['~my-username', 'my-username', true];
        yield ['~my.username', 'my.username', true];
        yield ['~My.useRname', 'My.useRname', true];
        yield [' ~my-user name  ', 'my-username', true];
        yield [' ~my!user#name@$%^&*()[]<>/<', 'myusername', true];
    }

    /** @dataProvider deprefixiseDataProvider */
    public function testDeprefixiseMethod(string $username, string $expected, bool $sanitise): void
    {
        $result = $this->service->deprefixise($username, $sanitise);

        $this->assertSame($expected, $result);
    }
}
