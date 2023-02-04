<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractKernelTestCase extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
