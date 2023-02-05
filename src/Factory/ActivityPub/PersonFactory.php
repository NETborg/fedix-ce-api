<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Factory\ActivityPub;

use Netborg\Fediverse\Api\Entity\User;
use Netborg\Fediverse\Api\Interfaces\ActivityPub\PersonFactoryInterface;
use Netborg\Fediverse\Api\Interfaces\ActivityPub\PublicKeyFactoryInterface;
use Netborg\Fediverse\Api\Interfaces\Sanitiser\UsernameSanitiserInterface;
use Netborg\Fediverse\Api\Model\ActivityPub\Actor\Person;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PersonFactory implements PersonFactoryInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly RouterInterface $router,
        private readonly PublicKeyFactoryInterface $publicKeyFactory,
        private readonly UsernameSanitiserInterface $sanitiser,
    ) {
    }

    public function fromJsonString(string $json, Person $subject = null, array $context = []): Person
    {
        return $this->serializer->deserialize($json, Person::class, 'json', $context);
    }

    public function fromArray(array $data, Person $subject = null, array $context = []): Person
    {
        /** @var Person $person */
        $person = $this->serializer->denormalize(data: $data, type: Person::class, context: $context);

        if ($subject) {
            $this->merge($person, $subject);

            return $subject;
        }

        return $person;
    }

    public function fromUserEntity(User $entity, Person $subject = null): Person
    {
        $options = [
            'identifier' => $this->sanitiser->prefixise($entity->getUsername()),
        ];

        $id = $this->router->generate('api_ap_v1_person_get', $options);
        $inbox = $this->router->generate('api_ap_v1_person_inbox_get', $options);
        $outbox = $this->router->generate('api_ap_v1_person_outbox_get', $options);
        $publicKey = $entity->getPublicKey()
            ? $this->publicKeyFactory->create(
                $this->router->generate('api_ap_v1_person_pub_key_get', $options),
                $id,
                $entity->getPublicKey()
            )
            : null;

        $person = $subject ?? new Person();

        return $person
            ->setId($id)
            ->setName($entity->getName())
            ->setPreferredUsername(str_replace('~', '', $entity->getUsername()))
            ->setInbox($inbox)
            ->setOutbox($outbox)
            ->setPublicKey($publicKey)
        ;
    }

    private function merge(Person $source, Person $target): void
    {
        $reflection = new \ReflectionClass(Person::class);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $setters = array_filter($methods, fn(\ReflectionMethod $reflectionMethod) => str_starts_with($reflectionMethod->getName(), 'set'));

        foreach ($setters as $reflectionMethod) {
            $getter = str_replace('set', 'get', $reflectionMethod->getName());
            if ($reflection->hasMethod($getter)) {
                $target->{$reflectionMethod->getName()}($source->{$getter}());
                continue;
            }

            $iser = str_replace('set', 'is', $reflectionMethod->getName());
            if ($reflection->hasMethod($iser)) {
                $target->{$reflectionMethod->getName()}($source->{$iser}());
                continue;
            }

            $haser = str_replace('set', 'has', $reflectionMethod->getName());
            if ($reflection->hasMethod($haser)) {
                $target->{$reflectionMethod->getName()}($source->{$haser}());
            }
        }
    }
}
