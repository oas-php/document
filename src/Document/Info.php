<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Serializable;
use OAS\Utils\Node;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#infoObject
 */
final class Info extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $title;
    protected string $version;
    protected ?string $summary;
    protected ?string $description;
    protected ?string $termsOfService;
    protected ?Contact $contact;
    protected ?License $license;

    public function __construct(
        string $title,
        string $version,
        string $summary = null,
        string $description = null,
        string $termsOfService = null,
        Contact $contact = null,
        License $license = null
    ) {
        $this->title = $title;
        $this->version = $version;
        $this->summary = $summary;
        $this->description = $description;
        $this->termsOfService = $termsOfService;
        $this->contact = $contact;
        !is_null($contact) && $this->__connect($contact);
        $this->license = $license;
        !is_null($license) && $this->__connect($license);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function hasSummary(): bool
    {
        return !is_null($this->summary);
    }


    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function hasDescription(): bool
    {
        return !is_null($this->description);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function hasTermsOfService(): bool
    {
        return !is_null($this->termsOfService);
    }

    public function getTermsOfService(): ?string
    {
        return $this->termsOfService;
    }

    public function hasContact(): bool
    {
        return !is_null($this->contact);
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function hasLicense(): bool
    {
        return !is_null($this->license);
    }

    public function getLicense(): ?License
    {
        return $this->license;
    }
}
