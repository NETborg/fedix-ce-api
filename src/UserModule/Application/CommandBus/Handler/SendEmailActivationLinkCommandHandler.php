<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\SendEmailActivationLinkCommand;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Factory\ActivationLinkFactory;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\ActivationLinkRepositoryInterface;

class SendEmailActivationLinkCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'activation_link.send';

    public function __construct(
        private readonly ActivationLinkFactory $activationLinkFactory,
        private readonly ActivationLinkRepositoryInterface $activationLinkRepository
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

        $activationLink = $this->activationLinkFactory->create($user);

        $this->activationLinkRepository->save($activationLink, true);

        // TODO - add sending email on the queue procedure here

        return $activationLink;
    }
}
