<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\Unit;

use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractUnitTestCase extends KernelTestCase
{
    use ProphecyTrait;
}
