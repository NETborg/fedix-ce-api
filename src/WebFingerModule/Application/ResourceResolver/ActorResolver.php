<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\ResourceResolver;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\ActorRepositoryInterface;
use Netborg\Fediverse\Api\Shared\Domain\Sanitiser\UsernameSanitiserInterface;
use Netborg\Fediverse\Api\WebFingerModule\Application\Builder\WebFingerResultBuilderInterface;
use Netborg\Fediverse\Api\WebFingerModule\Application\Exception\ResourceIdentifierException;
use Netborg\Fediverse\Api\WebFingerModule\Application\Exception\ResourceNotFoundException;
use Netborg\Fediverse\Api\WebFingerModule\Application\Exception\ResourceOwnershipException;
use Symfony\Component\Routing\RouterInterface;

class ActorResolver implements ResourceResolverInterface
{
    private const SCHEME = 'acct';

    public function __construct(
        private readonly ActorRepositoryInterface $actorRepository,
        private readonly UsernameSanitiserInterface $usernameSanitiser,
        private readonly RouterInterface $router,
        private readonly string $myDomain,
        private readonly string $frontendHost
    ) {
    }

    /** @param string[]|null $rel */
    public function supports(string $scheme, string $subject, string $resourceIdentifier, ?array $rel): bool
    {
        return self::SCHEME === $scheme;
    }

    /** @param string[]|null $rel */
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

        $subjectUrl = $this->router->generate('api_ap_v1_person_get', ['identifier' => $username], RouterInterface::ABSOLUTE_URL);

        $resultBuilder
            ->setSubject($subjectUrl)
            ->addLink(
                rel: 'http://webfinger.net/rel/#profile-page',
                href: sprintf('%s/%s/%s', $this->frontendHost, strtolower($actor->getType()), $username),
                type: 'text/html'
            );
        // TODO - add other WebFinger's resolutions here
        $resultBuilder->addLink(
            rel: 'http://webfinger.net/rel/#avatar',
            href: sprintf('%s/%s/%s', $this->frontendHost, 'avatar', $username),
            type: 'image/jpeg'
        );
    }
}
