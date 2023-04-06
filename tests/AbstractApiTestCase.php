<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractApiTestCase extends WebTestCase
{
    use PHPMatcherAssertions;
    use UserLoggerTrait;
    use ClientLoggerTrait;
    use FakerTrait;
}
