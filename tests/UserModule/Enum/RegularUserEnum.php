<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\UserModule\Enum;

enum RegularUserEnum
{
    public const USERNAME = 'RegularUser';
    public const EMAIL = 'test1@test.com';
    public const FIRST_NAME = 'Regular';
    public const LAST_NAME = 'User';
    public const PASSWORD = '12345678';
}
