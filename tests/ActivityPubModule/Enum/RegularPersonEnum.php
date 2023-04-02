<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\ActivityPubModule\Enum;

enum RegularPersonEnum
{
    public const UUID = '018729d4-32c1-7ac9-873e-813bf86833b9';
    public const NAME = 'Regular Person';
    public const NAME_PL = 'Normalna Osoba';
    public const USERNAME = 'RegularPerson';
    public const SUMMARY = 'I\'m just Regular Person summary';
    public const SUMMARY_PL = "Jestem tylko Normalną Osobą";
    public const CONTENT = "<div>Some Regular Person text content</div>";
    public const CONTENT_PL = "<div>Jakaś teksotwa zawartość Normalnej Osoby</div>";
}
