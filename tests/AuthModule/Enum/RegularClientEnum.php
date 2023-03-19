<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\AuthModule\Enum;

enum RegularClientEnum
{
    public const NAME = 'Client Regular';
    public const IDENTIFIER = '11111111111111111111111111111111';
    public const SECRET = 'S3kr3TPas5w0rd';
    public const REDIRECT_URI = 'https://zion.social/auth';
}
