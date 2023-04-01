<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\DataFixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person as DomainPerson;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Person;
use Netborg\Fediverse\Api\Tests\ActivityPubModule\Enum\RegularPersonEnum;
use Netborg\Fediverse\Api\Tests\UserModule\Enum\RegularUserEnum;


class PersonFixture extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $person = (new Person())
            ->setType(DomainPerson::TYPE)
            ->setUuid(RegularPersonEnum::UUID)
            ->setName(RegularPersonEnum::NAME)
            ->setPreferredUsername(RegularPersonEnum::USERNAME)
            ->setSummary(RegularPersonEnum::SUMMARY)
            ->addUser(RegularUserEnum::UUID)
        ;

        $manager->persist($person);
        $manager->flush();
    }
}