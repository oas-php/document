<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#tag-object
 */
final class Tag extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $name;
    protected ?string $description;
    protected ?ExternalDocumentation $externalDocumentation;

    public function __construct(
        string $name,
        string $description = null,
        ExternalDocumentation $externalDocumentation = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->externalDocumentation = $externalDocumentation;
        !is_null($externalDocumentation) && $this->__connect($externalDocumentation);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasDescription(): bool
    {
        return !is_bool($this->description);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function hasExternalDocumentation(): bool
    {
        return !is_null($this->externalDocumentation);
    }

    public function getExternalDocumentation(): ?ExternalDocumentation
    {
        return $this->externalDocumentation;
    }
}
