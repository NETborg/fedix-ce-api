<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Interfaces\ActivityPub;

use Netborg\Fediverse\Api\Model\ActivityPub\Subject\Profile;

interface ProfileFactoryInterface
{
    public function fromJsonString(string $json, Profile $subject = null, array $context = []): Profile;
    public function fromArray(array $data, Profile $subject = null, array $context = []): Profile;
    public function fromUserProfileEntity(UserProfile $entity, Profile $subject = null): Profile;
}
