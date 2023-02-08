<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFinger;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WebFingerServer implements WebFingerServerInterface
{
    public function __construct(
        private readonly WebFingerServiceInterface $webFingerService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function resolve(Request $request): JsonResponse
    {
        $resource = urldecode((string) $request->query->get('resource'));
        $rel = $request->query->get('rel');

        $errors = $this->validator->validate(['resource' => $resource], ['resource' => new All([
                new NotBlank(message: 'Resource must be provided and can not be an empty string'),
                new Regex(pattern: '/^(acct|https?):.+/i', message: 'Unsupported resource scheme. Sorry!'),
            ])]
        );

        if ($errors->count()) {
            return new JsonResponse($this->serializer->normalize($errors), Response::HTTP_BAD_REQUEST);
        }

        $result = $this->webFingerService->resolve($resource, $rel);

        return new JsonResponse($this->serializer->normalize($result));
    }
}
