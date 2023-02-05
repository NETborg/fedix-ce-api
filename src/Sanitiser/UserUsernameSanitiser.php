<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Sanitiser;

use Netborg\Fediverse\Api\Interfaces\Sanitiser\UsernameSanitiserInterface;

class UserUsernameSanitiser implements UsernameSanitiserInterface
{
    public function __construct(
        private readonly string $usernamePrefix,
        private readonly string $usernameRegexPattern
    ) {
    }

    public function getUsernamePrefix(): string
    {
        return $this->usernamePrefix;
    }

    public function getRegexPattern(): string
    {
        return sprintf('/^%s[%s]+$/', $this->usernamePrefix, $this->usernameRegexPattern);
    }

    public function sanitise(string $username): string
    {
        return preg_replace(sprintf('/[^%s]/', $this->usernameRegexPattern), '', $username);
    }

    public function prefixise(string $username): string
    {
        return sprintf('%s%s', $this->usernamePrefix, $this->sanitise($username));
    }

    public function deprefixise(string $username, bool $sanitise = false): string
    {
        $username = trim($username);

        if (str_starts_with($username, $this->usernamePrefix)) {
            $username = substr($username, strlen($this->usernamePrefix));
        }

        return $sanitise ? $this->sanitise($username) : $username;
    }
}
