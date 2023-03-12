<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Domain\Enum;

enum ScopeEnum
{
    public const REGISTER_USERS = 'client.register_users';
    public const USER_EMAIL = 'user.email';
    public const USER_PROFILE = 'user.profile';
    public const USER_POSTS_READ = 'user.posts_read';
    public const USER_POSTS_WRITE = 'user.posts_write';
}
