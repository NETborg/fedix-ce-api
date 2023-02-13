<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject\Profile;
use Netborg\Fediverse\Api\Interfaces\ActivityPub\UserProfile;

interface ProfileFactoryInterface
{
    public function fromJsonString(string $json, Profile $subject = null, array $context = []): Profile;

    public function fromArray(array $data, Profile $subject = null, array $context = []): Profile;

    public function fromUserProfileEntity(UserProfile $entity, Profile $subject = null): Profile;
}
