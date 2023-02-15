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
use Netborg\Fediverse\Api\UserModule\Application\Factory\DomainUserFactory;
use Netborg\Fediverse\Api\UserModule\Application\Factory\UserEntityFactory;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'user.register';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserEntityFactory $entityFactory,
        private readonly DomainUserFactory $domainUserFactory,
        private readonly ValidatorInterface $validator
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

        $entity = $this->entityFactory->fromRegisterUserDTO($registerUserDTO);

        $errors = $this->validator->validate(value: $entity, groups: ['Create']);
        if ($errors->count()) {
            throw new ValidationFailedException($entity, $errors);
        }

        $this->userRepository->save($entity, true);

        $activationLink = $this->commandBus->handle(new SendEmailActivationLinkCommand($entity));

        if (!$activationLink) {
            $this->logger->error(sprintf('Unable to send email confirmation to %s', $entity->getEmail()));
            throw new \Exception('Unable to send confirmation email on email provided!');
        }

        return new RegisteredUserDTO(
            $this->domainUserFactory->fromEntity($entity),
            (bool) $activationLink
        );
    }
}
