<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#external-documentation-object
 */
final class ExternalDocumentation extends Node implements \JsonSerializable
{
    use  Serializable;

    protected string $url;
    protected ?string $description;

    public function __construct(string $url, string $description = null)
    {
        $this->url = $url;
        $this->description = $description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function hasDescription(): bool
    {
        return !is_null($this->description);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
