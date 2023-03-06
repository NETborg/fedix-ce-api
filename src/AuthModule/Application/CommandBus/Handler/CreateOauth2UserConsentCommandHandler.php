<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\AuthModule\Application\CommandBus\Command\CreateOauth2UserConsentCommand;
use Netborg\Fediverse\Api\AuthModule\Application\Factory\Oauth2UserConsentEntityFactory;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Oauth2UserConsent;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\Repository\Oauth2UserConsentRepository;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;

class CreateOauth2UserConsentCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'oauth2_consent.create';

    public function __construct(
        private readonly Oauth2UserConsentRepository $consentRepository,
        private readonly Oauth2UserConsentEntityFactory $consentEntityFactory,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return CreateOauth2UserConsentCommand::NAME === $command
            && Oauth2UserConsent::class === $subjectType;
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var Oauth2UserConsent $userConsent */
        $userConsent = $command->getSubject();

        $entity = $this->consentEntityFactory->fromModel($userConsent);

        $this->consentRepository->save($entity, true);

        return true;
    }
}
