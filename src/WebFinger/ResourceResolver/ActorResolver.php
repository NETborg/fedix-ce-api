<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFinger\ResourceResolver;

use Netborg\Fediverse\Api\Exception\ResourceIdentifierException;
use Netborg\Fediverse\Api\Exception\ResourceNotFoundException;
use Netborg\Fediverse\Api\Exception\ResourceOwnershipException;
use Netborg\Fediverse\Api\Interfaces\Repository\ActorRepositoryInterface;
use Netborg\Fediverse\Api\Interfaces\Sanitiser\UsernameSanitiserInterface;
use Netborg\Fediverse\Api\WebFinger\ResourceResolverInterface;
use Netborg\Fediverse\Api\WebFinger\WebFingerResultBuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class ActorResolver implements ResourceResolverInterface
{
    private const SCHEME = 'acct';

    public function __construct(
        private readonly ActorRepositoryInterface $actorRepository,
        private readonly UsernameSanitiserInterface $usernameSanitiser,
        private readonly RouterInterface $router,
        private string $myDomain,
    ) {
    }

    public function supports(string $scheme, string $subject, string $resourceIdentifier, ?array $rel): bool
    {
        return self::SCHEME === $scheme;
    }

    public function resolve(
        string $scheme,
        string $subject,
        string $resourceIdentifier,
        ?array $rel,
        WebFingerResultBuilderInterface $resultBuilder
    ): void {
        [$username, $host] = explode('@', $subject);

        if (empty($username) || empty($host)) {
            throw ResourceIdentifierException::create($subject);
        }

        $parsed = parse_url($host);
        $host = $parsed['host'] ?? $parsed['path'] ?? null;

        if ($host !== $this->myDomain) {
            throw ResourceOwnershipException::create($subject);
        }

        $username = $this->usernameSanitiser->deprefixise($username, true);

        $actor = $this->actorRepository->findOneByPreferredUsername($username);

        if (!$actor) {
            throw ResourceNotFoundException::actor($subject);
        }

        $username = $this->usernameSanitiser->prefixise($username);

        $subject = $this->router->generate('api_ap_v1_person_get', ['identifier' => $username], RouterInterface::ABSOLUTE_URL);

        // TODO - add other WebFinger's resolutions here
        $resultBuilder
            ->setSubject($subject);
    }
}
