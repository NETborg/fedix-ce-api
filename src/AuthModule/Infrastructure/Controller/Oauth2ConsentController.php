<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Controller;

use Netborg\Fediverse\Api\AuthModule\Application\CommandBus\Command\CreateOauth2UserConsentCommand;
use Netborg\Fediverse\Api\AuthModule\Application\Model\DTO\GetUserConsentDTO;
use Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Query\GetOauth2ClientQuery;
use Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Query\GetOauth2UserConsentQuery;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Client;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Oauth2UserConsent;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\AuthenticatedUser\DoctrineEntityUser;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\Shared\Infrastructure\Controller\AbstractController;
use Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query\GetUserQuery;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class Oauth2ConsentController extends AbstractController
{
    private const CONSENT_GRANTED = 'consent_granted';
    private const QUERY_CACHE = '_query';

    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(Request $request)
    {
        $clientId = $request->query->get('client_id');
        if (!$clientId || !ctype_alnum($clientId) || !$this->getUser()) {
            $this->logger->warning('Could not find required `client_id` query parameter. Redirecting to `app_index`.');

            return $this->redirectToRoute('app_index');
        }

        /** @var Client|null $appClient */
        $appClient = $this->queryBus->handle(new GetOauth2ClientQuery($clientId));
        if (!$appClient) {
            $this->logger->warning(sprintf(
                'Could not find client with ID: %s. Redirecting to `app_index`.',
                $clientId
            ));

            return $this->redirectToRoute('app_index');
        }

        $session = $request->getSession();
        $session->set(self::QUERY_CACHE, $request->query->all());

        // Get the client scopes
        $requestedScopes = array_filter(
            explode(' ', $request->query->get('scope')),
            fn(string $scope) => str_starts_with($scope, 'user.')
        );

        // Get the client scopes in the database
        $clientScopes = $appClient->getScopes();

        // Check all requested scopes are in the client scopes
        if (count(array_diff($requestedScopes, $clientScopes)) > 0) {
            $this->logger->warning(
                'Requested scope not included in client\'s scope allowance. Redirecting to `app_index`.'
            );

            return $this->redirectToRoute('app_index');
        }

        // Check if the user has already consented to the scopes
        /** @var User $user */
        $user = $this->queryBus->handle(new GetUserQuery($this->getUser()->getUserIdentifier()));

        $userConsentDTO = new GetUserConsentDTO();
        $userConsentDTO->user = $user->getUuid();
        $userConsentDTO->client = $clientId;

        /** @var Oauth2UserConsent|null $userConsent */
        $userConsent = $this->queryBus->handle(new GetOauth2UserConsentQuery($userConsentDTO));

        $userScopes = $userConsent?->getScopes() ?? [];
        $hasExistingScopes = count($userScopes) > 0;

        // If user has already consented to the scopes, give consent
        if (count(array_diff($requestedScopes, $userScopes)) === 0) {
            $session->set(self::CONSENT_GRANTED, true);
            $this->logger->debug('User already approved client\'s consent.');
            $this->logger->debug(sprintf(
            ' [1] Setting user\'s consent: %s. Redirecting back to authorization route.',
            $session->get(self::CONSENT_GRANTED) ? 'APPROVED' : 'REJECTED'
            ));

            $query = $session->remove(self::QUERY_CACHE) ?? $request->query->all();

            return $this->redirectToRoute('oauth2_authorize', $query);
        }

        // Remove the scopes to which the user has already consented
        $requestedScopes = array_diff($requestedScopes, $userScopes);

        // Map the requested scopes to scope names
        $scopeNames = [
            'user.email' => 'Your email address',
            'user.profile' => 'Your profile details',
            'user.first_name' => 'Your first name',
            'user.last_name' => 'Your last name',
            'user.posts_read' => 'Your blog posts (read)',
            'user.posts_write' => 'Your blog posts (write)',
        ];

        // Get all the scope names in the requested scopes.
        $requestedScopeNames = array_map(fn($scope) => $scopeNames[$scope], $requestedScopes);
        $existingScopes = array_map(fn($scope) => $scopeNames[$scope], $userScopes);

        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->set(self::CONSENT_GRANTED, false);

            if ($request->request->get('consent') === 'yes') {
                // Add the requested scopes to the user's scopes
                $consent = $userConsent ?? new OAuth2UserConsent();;
                $consent->setScopes(array_merge($requestedScopes, $userScopes));
                $consent->setClient($appClient);
                $consent->setUser($user);

                $this->commandBus->handle(new CreateOauth2UserConsentCommand($consent));
                $session->set(self::CONSENT_GRANTED, true);
                $this->logger->debug('User approved client\'s consent.');
            }
            $this->logger->debug(sprintf(
                '[2] Setting user\'s consent: %s. Redirecting back to authorization route.',
                $session->get(self::CONSENT_GRANTED) ? 'APPROVED' : 'REJECTED'
            ));

            $query = $session->remove(self::QUERY_CACHE) ?? $request->query->all();

            return $this->redirectToRoute('oauth2_authorize', $query);
        }

        return $this->render('auth/oauth2_consent.html.twig', [
            'client_name' => $appClient->getName(),
            'scopes' => $requestedScopeNames,
            'has_existing_scopes' => $hasExistingScopes,
            'existing_scopes' => $existingScopes,
        ]);
    }
}
