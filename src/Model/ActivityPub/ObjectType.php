<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub;

use DateTimeInterface;
use Netborg\Fediverse\Api\Model\ActivityPub\Subject\Image;
use Symfony\Component\Validator\Constraints as Assert;

abstract class ObjectType
{
    protected static string $type = 'ObjectType';

    #[Assert\AtLeastOneOf([
        new Assert\Url(),
        new Assert\Uuid(),
    ])]
    protected string|null $id = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null  */
    protected string|LinkType|ObjectType|array|null $attachment = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null  */
    protected string|LinkType|ObjectType|array|null $attributedTo = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null */
    protected string|LinkType|ObjectType|array|null $audience = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null */
    protected string|LinkType|ObjectType|array|null $to = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null */
    protected string|LinkType|ObjectType|array|null $bcc = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null */
    protected string|LinkType|ObjectType|array|null $bto = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null */
    protected string|LinkType|ObjectType|array|null $cc = null;
    protected string|null $content = null;

    /** @var array<string, string>|null  */
    protected array|null $contentMap = null;
    protected string|LinkType|ObjectType|null $context = null;
    protected string|DateTimeInterface|null $duration = null;
    protected string|DateTimeInterface|null $startTime = null;
    protected string|DateTimeInterface|null $endTime = null;
    protected string|LinkType|ObjectType|null $generator = null;

    /** @var string|Image|LinkType|array<Image|LinkType|string>|null  */
    protected string|Image|LinkType|array|null $icon = null;

    /** @var string|Image|LinkType|array<Image|LinkType|string>|null  */
    protected string|Image|LinkType|array|null $image = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null  */
    protected string|LinkType|ObjectType|array|null $inReplyTo = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null  */
    protected string|LinkType|ObjectType|array|null $location;

    #[
        Assert\Regex(
            pattern: '/^(text|application|audio|video|image|message|model|multipart)\/[a-z0-9+\-\.]+$/',
            groups: ['Default', 'Create', 'Update']
        )
    ]
    protected string|null $mediaType = null;
    protected string|null $name = null;

    /** @var array<string,string>|null  */
    protected array|null $nameMap = null;
    protected string|LinkType|ObjectType|null $preview = null;
    protected string|DateTimeInterface|null $published = null;
    protected string|Collection|null $replies = null;
    protected string|null $summary = null;

    /** @var array<string,string>|null  */
    protected array|null $summaryMap = null;

    /** @var string|LinkType|ObjectType|array<LinkType|ObjectType|string>|null  */
    protected string|LinkType|ObjectType|array|null $tag;

    protected string|DateTimeInterface|null $updated = null;

    /** @var string|LinkType|LinkType[]|null  */
    protected string|LinkType|array|null $url = null;

    public function getType(): string
    {
        return static::$type;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): ObjectType
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getAttachment(): LinkType|array|string|ObjectType|null
    {
        return $this->attachment;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $attachment
     */
    public function setAttachment(LinkType|array|string|ObjectType|null $attachment): ObjectType
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getAttributedTo(): LinkType|array|string|ObjectType|null
    {
        return $this->attributedTo;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $attributedTo
     */
    public function setAttributedTo(LinkType|array|string|ObjectType|null $attributedTo): ObjectType
    {
        $this->attributedTo = $attributedTo;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getAudience(): LinkType|array|string|ObjectType|null
    {
        return $this->audience;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $audience
     */
    public function setAudience(LinkType|array|string|ObjectType|null $audience): ObjectType
    {
        $this->audience = $audience;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getTo(): LinkType|array|string|ObjectType|null
    {
        return $this->to;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $to
     */
    public function setTo(LinkType|array|string|ObjectType|null $to): ObjectType
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getBcc(): LinkType|array|string|ObjectType|null
    {
        return $this->bcc;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $bcc
     */
    public function setBcc(LinkType|array|string|ObjectType|null $bcc): ObjectType
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getBto(): LinkType|array|string|ObjectType|null
    {
        return $this->bto;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $bto
     */
    public function setBto(LinkType|array|string|ObjectType|null $bto): ObjectType
    {
        $this->bto = $bto;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getCc(): LinkType|array|string|ObjectType|null
    {
        return $this->cc;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $cc
     */
    public function setCc(LinkType|array|string|ObjectType|null $cc): ObjectType
    {
        $this->cc = $cc;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): ObjectType
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return array<string,string>|null
     */
    public function getContentMap(): ?array
    {
        return $this->contentMap;
    }

    /**
     * @param array<string,string>|null $contentMap
     */
    public function setContentMap(?array $contentMap): ObjectType
    {
        $this->contentMap = $contentMap;

        return $this;
    }

    public function getContext(): LinkType|string|ObjectType|null
    {
        return $this->context;
    }

    public function setContext(LinkType|string|ObjectType|null $context): ObjectType
    {
        $this->context = $context;

        return $this;
    }

    public function getDuration(): string|DateTimeInterface|null
    {
        return $this->duration;
    }

    public function setDuration(string|DateTimeInterface|null $duration): ObjectType
    {
        $this->duration = $duration;

        return $this;
    }

    public function getStartTime(): string|DateTimeInterface|null
    {
        return $this->startTime;
    }

    public function setStartTime(string|DateTimeInterface|null $startTime): ObjectType
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): string|DateTimeInterface|null
    {
        return $this->endTime;
    }

    public function setEndTime(string|DateTimeInterface|null $endTime): ObjectType
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getGenerator(): LinkType|string|ObjectType|null
    {
        return $this->generator;
    }

    public function setGenerator(LinkType|string|ObjectType|null $generator): ObjectType
    {
        $this->generator = $generator;

        return $this;
    }

    /**
     * @return array<Image,LinkType,string>|Image|LinkType|string|null
     */
    public function getIcon(): LinkType|array|string|Image|null
    {
        return $this->icon;
    }

    /**
     * @param array<Image,LinkType,string>|Image|LinkType|string|null $icon
     */
    public function setIcon(LinkType|array|string|Image|null $icon): ObjectType
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return array<Image,LinkType,string>|Image|LinkType|string|null
     */
    public function getImage(): LinkType|array|string|Image|null
    {
        return $this->image;
    }

    /**
     * @param array<Image,LinkType,string>|Image|LinkType|string|null $image
     */
    public function setImage(LinkType|array|string|Image|null $image): ObjectType
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getInReplyTo(): LinkType|array|string|ObjectType|null
    {
        return $this->inReplyTo;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $inReplyTo
     */
    public function setInReplyTo(LinkType|array|string|ObjectType|null $inReplyTo): ObjectType
    {
        $this->inReplyTo = $inReplyTo;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getLocation(): LinkType|array|string|ObjectType|null
    {
        return $this->location;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $location
     */
    public function setLocation(LinkType|array|string|ObjectType|null $location): ObjectType
    {
        $this->location = $location;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(?string $mediaType): ObjectType
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ObjectType
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array<string,string>|null
     */
    public function getNameMap(): ?array
    {
        return $this->nameMap;
    }

    /**
     * @param array<string,string>|null $nameMap
     */
    public function setNameMap(?array $nameMap): ObjectType
    {
        $this->nameMap = $nameMap;

        return $this;
    }

    public function getPreview(): LinkType|string|ObjectType|null
    {
        return $this->preview;
    }

    public function setPreview(LinkType|string|ObjectType|null $preview): ObjectType
    {
        $this->preview = $preview;

        return $this;
    }

    public function getPublished(): DateTimeInterface|string|null
    {
        return $this->published;
    }

    public function setPublished(DateTimeInterface|string|null $published): ObjectType
    {
        $this->published = $published;

        return $this;
    }

    public function getReplies(): Collection|string|null
    {
        return $this->replies;
    }

    public function setReplies(Collection|string|null $replies): ObjectType
    {
        $this->replies = $replies;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): ObjectType
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return array<string,string>|null
     */
    public function getSummaryMap(): ?array
    {
        return $this->summaryMap;
    }

    /**
     * @param array<string, string>|null $summaryMap
     */
    public function setSummaryMap(?array $summaryMap): ObjectType
    {
        $this->summaryMap = $summaryMap;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null
     */
    public function getTag(): LinkType|array|string|ObjectType|null
    {
        return $this->tag;
    }

    /**
     * @param array<LinkType|ObjectType|string>|LinkType|ObjectType|string|null $tag
     */
    public function setTag(LinkType|array|string|ObjectType|null $tag): ObjectType
    {
        $this->tag = $tag;

        return $this;
    }

    public function getUpdated(): DateTimeInterface|string|null
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeInterface|string|null $updated): ObjectType
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string|LinkType|LinkType[]|null
     */
    public function getUrl(): LinkType|array|string|null
    {
        return $this->url;
    }

    /**
     * @param string|LinkType|LinkType[]|null $url
     */
    public function setUrl(LinkType|array|string|null $url): ObjectType
    {
        $this->url = $url;

        return $this;
    }
}
