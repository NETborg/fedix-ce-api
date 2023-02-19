<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\RegisterUserCommand;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;
use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\RegisteredUserDTO;
use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\RegisterUserDTO;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\SendEmailActivationLinkCommand;
use Netborg\Fediverse\Api\UserModule\Application\Event\UserRegisteredEvent;
use Netborg\Fediverse\Api\UserModule\Application\Factory\UserFactory;
use Netborg\Fediverse\Api\UserModule\Application\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'user.register';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserFactory $userFactory,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return RegisterUserCommand::NAME === $command && RegisterUserDTO::class === $subjectType;
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var RegisterUserDTO $registerUserDTO */
        $registerUserDTO = $command->getSubject();

        $user = $this->userFactory->fromRegisterUserDTO($registerUserDTO);

        $this->userRepository->save($user);
        $this->messageBus->dispatch(UserRegisteredEvent::create($user));

        $activationLink = $this->commandBus->handle(new SendEmailActivationLinkCommand($user));

        if (!$activationLink) {
            $this->logger->error(sprintf('Unable to send email confirmation to %s', $user->getEmail()));
            throw new \Exception('Unable to send confirmation email on email provided!');
        }

        return new RegisteredUserDTO(
            $user,
            (bool) $activationLink
        );
    }
}
