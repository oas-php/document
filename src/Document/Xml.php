<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#xml-object
 */
final class Xml extends Node implements \JsonSerializable
{
    use Serializable;

    protected ?string $name;

    protected ?string $namespace;

    protected ?string $prefix;

    protected ?bool $attribute;

    protected ?bool $wrapped;

    public function __construct(
        string $name = null,
        string $namespace = null,
        string $prefix = null,
        bool $attribute = false,
        bool $wrapped = false
    ) {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->prefix = $prefix;
        $this->attribute = $attribute;
        $this->wrapped = $wrapped;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function isAttribute(): bool
    {
        return $this->attribute;
    }

    public function isWrapped(): bool
    {
        return $this->wrapped;
    }
}
