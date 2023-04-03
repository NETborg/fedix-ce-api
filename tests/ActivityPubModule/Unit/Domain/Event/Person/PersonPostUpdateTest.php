<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\ActivityPubModule\Unit\Domain\Event\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Collection\ChangedFieldsCollectionInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostUpdate;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\Tests\AbstractUnitTestCase;

/** @covers \Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostUpdate */
class PersonPostUpdateTest extends AbstractUnitTestCase
{
    public function testGetter(): void
    {
        $person = $this->prophesize(Person::class);
        $changedFieldsCollection = $this->prophesize(ChangedFieldsCollectionInterface::class);

        $event = new PersonPostUpdate($person->reveal(), $changedFieldsCollection->reveal());
        $resultPerson = $event->getCurrent();
        $resultCollection = $event->getChangedFieldsCollection();

        $this->assertInstanceOf(Person::class, $resultPerson);
        $this->assertInstanceOf(ChangedFieldsCollectionInterface::class, $resultCollection);
    }
}
