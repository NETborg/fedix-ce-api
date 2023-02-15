<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\Integration\WebFingerModule\Application\Builder;

use Netborg\Fediverse\Api\Tests\Integration\AbstractKernelTestCase;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerLink;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerProperties;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerResult;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerTitles;
use Netborg\Fediverse\Api\WebFingerModule\Application\Builder\WebFingerResultBuilder;

/** @covers \Netborg\Fediverse\Api\WebFingerModule\Application\Builder\WebFingerResultBuilder */
class WebFingerResultBuilderTest extends AbstractKernelTestCase
{
    public function testBuild(): void
    {
        $result = (new WebFingerResultBuilder())
            ->setSubject('my_subject')
            ->addAlias('alias_1')
            ->addAlias('alias_2')
            ->addAlias('alias_3')
            ->addAlias('alias_3')
            ->addAlias('alias_3')
            ->addProperty('prop1', 'some property 1 value')
            ->addProperty('prop2', 'some property 2 value')
            ->addProperty('prop2', 'some property 2 value')
            ->addProperty('prop2', 'some property 2 value')
            ->addLink(
                'rel1',
                'href_1',
                'type 1',
                ['en_GB' => 'Title EN', 'pl_PL' => 'Title PL'],
                ['some link prop 1' => 'link property 1', 'some link prop 2' => 'link property 2']
            )
            ->build()
        ;

        $expected = (new WebFingerResult())
            ->setSubject('my_subject')
            ->setAliases(['alias_1', 'alias_2', 'alias_3'])
            ->setProperties((new WebFingerProperties())->setProperties([
                'prop1' => 'some property 1 value',
                'prop2' => 'some property 2 value',
            ]))
            ->setLinks([
                (new WebFingerLink())
                    ->setRel('rel1')
                    ->setHref('href_1')
                    ->setType('type 1')
                    ->setTitles(
                        (new WebFingerTitles())
                        ->setProperties(['en_GB' => 'Title EN', 'pl_PL' => 'Title PL'])
                    )
                    ->setProperties(
                        (new WebFingerProperties())
                        ->setProperties(
                            ['some link prop 1' => 'link property 1', 'some link prop 2' => 'link property 2']
                        )
                    ),
            ]);

        $this->assertInstanceOf(WebFingerResult::class, $result);
        $this->assertEquals($expected, $result);
    }
}
