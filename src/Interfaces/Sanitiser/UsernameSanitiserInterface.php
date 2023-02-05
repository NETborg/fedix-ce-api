<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Interfaces\Sanitiser;

interface UsernameSanitiserInterface
{
    public function getUsernamePrefix(): string;

    public function getRegexPattern(): string;

    public function sanitise(string $username): string;

    public function prefixise(string $username): string;

    public function deprefixise(string $username, bool $sanitise = false): string;
}
