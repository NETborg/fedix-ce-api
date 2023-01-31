<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractKernelTestCase extends KernelTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }
}
