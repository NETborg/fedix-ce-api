<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\ActivityPubModule\Unit\Domain\Event\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostCreate;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\Tests\AbstractUnitTestCase;

/** @covers \Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostCreate */
class PersonPostCreateTest extends AbstractUnitTestCase
{
    public function testGetter(): void
    {
        $person = $this->prophesize(Person::class);

        $event = new PersonPostCreate($person->reveal());
        $result = $event->getPerson();

        $this->assertInstanceOf(Person::class, $result);
    }
}
