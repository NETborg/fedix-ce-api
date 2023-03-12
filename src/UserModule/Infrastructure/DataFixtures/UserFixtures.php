<?php

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Netborg\Fediverse\Api\Tests\AbstractKernelTestCase;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername(AbstractKernelTestCase::USERNAME_REGULAR_USER)
            ->setFirstName('Regular')
            ->setLastName('User')
            ->setPassword('12345678')
            ->setEmail('test1@test.com')
            ->setActive(true)
        ;

        $manager->persist($user);


        $manager->flush();
    }
}
