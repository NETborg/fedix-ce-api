<?php

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Netborg\Fediverse\Api\Tests\UserModule\Enum\RegularUserEnum;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername(RegularUserEnum::USERNAME)
            ->setFirstName(RegularUserEnum::FIRST_NAME)
            ->setLastName(RegularUserEnum::LAST_NAME)
            ->setPassword(RegularUserEnum::PASSWORD)
            ->setEmail(RegularUserEnum::EMAIL)
            ->setActive(true)
        ;

        $manager->persist($user);


        $manager->flush();
    }
}
