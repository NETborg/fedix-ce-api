<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests;

use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractUnitTestCase extends KernelTestCase
{
    use ProphecyTrait;
    use FakerTrait;
}
