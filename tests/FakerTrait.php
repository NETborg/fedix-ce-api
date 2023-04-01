<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    protected const FAKER_LOCALE = 'en_US';

    protected static ?Generator $faker = null;

    protected function getFaker(): Generator
    {
        return self::$faker ?? self::$faker = Factory::create(self::FAKER_LOCALE);
    }
}
