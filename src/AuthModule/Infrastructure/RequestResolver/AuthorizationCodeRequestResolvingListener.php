<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\RequestResolver;

use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\FirewallMap;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

#[AsEventListener(event: 'league.oauth2_server.event.authorization_request_resolve', method: 'onAuthorizationRequest')]
class AuthorizationCodeRequestResolvingListener
{
    use TargetPathTrait;

    private string $firewallName;

    public function __construct(
        private readonly Security $security,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack,
        private readonly FirewallMapInterface $firewallMap,
    ) {
    }

    public function onAuthorizationRequest(AuthorizationRequestResolveEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        /** @var FirewallMap $firewallMap */
        $firewallMap = $this->firewallMap;
        $this->firewallName = 'main'; //$firewallMap->getFirewallConfig($request)->getName();

        $user = $this->security->getUser();
        $this->saveTargetPath($request->getSession(), $this->firewallName, $request->getUri());

        $response = new RedirectResponse($this->urlGenerator->generate('app_login'), 307);
        if ($user instanceof UserInterface) {
            if ($request->getSession()->get('consent_granted') !== null) {
                $event->resolveAuthorization($request->getSession()->get('consent_granted'));
                $request->getSession()->remove('consent_granted');
                return;
            }

            $request->getSession()->set('consent_granted', true);
            return;

            $response = new RedirectResponse($this->urlGenerator->generate('app_consent', $request->query->all()), 307);
        }
        $event->setResponse($response);
    }
}
