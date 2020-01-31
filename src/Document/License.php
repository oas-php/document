<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#license-object
 */
final class License extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $name;
    protected ?string $identifier;
    protected ?string $url;

    public function __construct(string $name, string $identifier = null, string $url = null)
    {
        $this->name = $name;
        $this->identifier = $identifier;
        $this->url = $url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasIdentifier(): bool
    {
        return !is_null($this->identifier);
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function hasUrl(): bool
    {
        return !is_null($this->url);
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
