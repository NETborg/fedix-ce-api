<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\Integration\API;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractApiTestCase extends WebTestCase
{
    use PHPMatcherAssertions;
}
