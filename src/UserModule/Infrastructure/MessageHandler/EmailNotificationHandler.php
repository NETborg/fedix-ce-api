<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\MessageHandler;

use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query\GetActivationLinkQuery;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\ActivationLink;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Message\ActivationLinkNotification;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EmailNotificationHandler
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly LoggerInterface $logger,
        private readonly MailerInterface $mailer,
        private readonly Environment $twig
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[AsMessageHandler]
    public function handleActivationLinkNotification(ActivationLinkNotification $notification): void
    {
        /** @var ActivationLink|null $activationLinkEntity */
        $activationLinkEntity = $this->queryBus->handle(
            new GetActivationLinkQuery($notification->getActivationLinkId())
        );

        if (!$activationLinkEntity) {
            $msg = sprintf(
                'Unable to send Activation Link to user! Activation Link ID [%d] not found.',
                $notification->getActivationLinkId()
            );
            $this->logger->critical($msg);

            throw new UnrecoverableMessageHandlingException($msg);
        }

        $this->logger->debug(sprintf(
            'Processing Activation Link notification [%s]',
            $notification->getActivationLinkId()
        ));

        $email = (new Email())
            ->from('no-reply@fedix.com')
            ->to($activationLinkEntity->getUser()->getEmail())
            ->subject('Activation Link')
            ->html($this->twig->render('email/activation_link.html.twig', [
                'activationLinkUuid' => $activationLinkEntity->getUuid(),
            ]));

        $this->mailer->send($email);
    }
}
