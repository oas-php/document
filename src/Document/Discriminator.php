<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Serializable;
use OAS\Utils\Node;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#discriminator-object
 */
final class Discriminator extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $propertyName;
    protected ?array $mapping;

    public function __construct(string $propertyName, array $mapping = null)
    {
        $this->propertyName = $propertyName;
        $this->mapping = $mapping;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function hasMapping(): bool
    {
        return !\is_null($this->$this->mapping);
    }

    public function getMapping(): ?array
    {
        return $this->mapping;
    }
}
