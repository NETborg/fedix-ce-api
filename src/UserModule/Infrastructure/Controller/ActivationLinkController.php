<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Controller;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\ActivateLinkDTO;
use Netborg\Fediverse\Api\Shared\Infrastructure\Util\ViolationListToArrayConverter;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\ActivationLinkCommand;
use Netborg\Fediverse\Api\UserModule\Domain\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ActivationLinkController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function getAction(string $identifier): JsonResponse
    {
        $activateLinkDTO = new ActivateLinkDTO($identifier);

        $errors = $this->validator->validate($activateLinkDTO);
        if ($errors->count()) {
            throw new ValidationException(ViolationListToArrayConverter::convert($errors));
        }

        $result = $this->commandBus->handle(new ActivationLinkCommand($activateLinkDTO));

        return new JsonResponse(['activation_status' => $result]);
    }
}
