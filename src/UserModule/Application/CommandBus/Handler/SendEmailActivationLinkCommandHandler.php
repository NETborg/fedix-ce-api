<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\SendEmailActivationLinkCommand;
use Netborg\Fediverse\Api\UserModule\Application\Factory\ActivationLinkFactory;
use Netborg\Fediverse\Api\UserModule\Application\Repository\ActivationLinkRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Message\ActivationLinkNotification;
use Symfony\Component\Messenger\MessageBusInterface;

class SendEmailActivationLinkCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'activation_link.send';

    public function __construct(
        private readonly ActivationLinkFactory $activationLinkFactory,
        private readonly ActivationLinkRepositoryInterface $activationLinkRepository,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return SendEmailActivationLinkCommand::NAME === $command && User::class === $subjectType;
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var User $user */
        $user = $command->getSubject();

        $activationLink = $this->activationLinkFactory->createNew($user);

        $this->activationLinkRepository->save($activationLink);

        $this->messageBus->dispatch(new ActivationLinkNotification($activationLink->getUuid()));

        return $activationLink;
    }
}
