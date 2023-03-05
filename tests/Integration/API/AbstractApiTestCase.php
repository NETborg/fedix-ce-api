<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\Integration\API;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Netborg\Fediverse\Api\Tests\Integration\ClientLoggerTrait;
use Netborg\Fediverse\Api\Tests\Integration\UserLoggerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractApiTestCase extends WebTestCase
{
    use PHPMatcherAssertions;
    use UserLoggerTrait;
    use ClientLoggerTrait;
}
