<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\UpdateActivationLinkCommand;
use Netborg\Fediverse\Api\UserModule\Application\Repository\ActivationLinkRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink;

class UpdateActivationLinkCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'activation_link.update';

    public function __construct(
        private readonly ActivationLinkRepositoryInterface $activationLinkRepository
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return UpdateActivationLinkCommand::NAME === $command
            && ActivationLink::class === $subjectType;
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var ActivationLink $activationLink */
        $activationLink = $command->getSubject();

        $this->activationLinkRepository->save($activationLink);

        return $activationLink;
    }
}
