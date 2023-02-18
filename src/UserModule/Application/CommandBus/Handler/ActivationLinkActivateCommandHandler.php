<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;
use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\ActivateLinkDTO;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\ActivationLinkCommand;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\DeleteActivationLinkCommand;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\UpdateUserCommand;
use Netborg\Fediverse\Api\UserModule\Application\Event\UserActivatedEvent;
use Netborg\Fediverse\Api\UserModule\Application\Exception\InvalidActivationLinkException;
use Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query\GetActivationLinkQuery;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\ActivationLink;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ActivationLinkActivateCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'activation_link.activate';

    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return ActivationLinkCommand::NAME === $command && ActivateLinkDTO::class === $subjectType;
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var ActivateLinkDTO $activateLinkDTO */
        $activateLinkDTO = $command->getSubject();

        /** @var ActivationLink|null $activationLinkEntity */
        $activationLinkEntity = $this->queryBus->handle(new GetActivationLinkQuery($activateLinkDTO));

        if (!$activationLinkEntity) {
            throw InvalidActivationLinkException::notFound();
        }

        if ($activationLinkEntity->getExpiresAt() < new \DateTimeImmutable()) {
            throw InvalidActivationLinkException::expired();
        }

        $user = $activationLinkEntity->getUser()->setActive(true);

        if (!$this->commandBus->handle(new UpdateUserCommand($user))) {
            $this->logger->critical(sprintf('Unable to update User [%s] during account activation', $user->getUuid()));

            return false;
        }

        if (!$this->commandBus->handle(new DeleteActivationLinkCommand($activationLinkEntity))) {
            $this->logger->error(sprintf('Unable to delete Activation Link [%s] during account activation', $activationLinkEntity->getUuid()));
        }

        $this->messageBus->dispatch(UserActivatedEvent::create($user));

        return $user->isActive();
    }
}
