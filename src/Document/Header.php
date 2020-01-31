<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Schema;
use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#header-object
 */
class Header extends Node implements \JsonSerializable
{
    use Serializable;

    private const DEFAULT = [
        'deprecated' => false,
        'allowEmptyValue' => false
    ];

    protected string $name;

    protected bool $required;

    protected ?string $description;

    protected ?bool $deprecated;

    protected ?bool $allowEmptyValue;

    protected ?Schema $schema;

    /**
     * TODO
     *  - style
     *  - explode
     *  - allowReserved
     *  - example
     *  - examples
     *  - content
     */
    public function __construct(
        string $name,
        bool $required,
        string $description = null,
        bool $deprecated = null,
        bool $allowEmptyValue = null,
        Schema $schema = null
    ) {
        $this->name = $name;
        $this->required = $required;
        $this->description = $description;
        $this->deprecated = $deprecated;
        $this->allowEmptyValue = $allowEmptyValue;
        $this->schema = $schema;
    }

    public function getName(): string
    {
        return $this->ref()->name;
    }

    public function isRequired(): bool
    {
        return $this->ref()->required;
    }

    public function getRequired(): bool
    {
        return $this->ref()->required;
    }

    public function hasDescription(): bool
    {
        return !is_null($this->ref()->description);
    }

    public function getDescription(): ?string
    {
        return $this->ref()->description;
    }

    public function isDeprecated(): bool
    {
        return $this->hasDeprecated() ? $this->ref()->deprecated : self::DEFAULT['deprecated'];
    }

    public function hasDeprecated(): bool
    {
        return !is_null($this->ref()->deprecated);
    }

    public function getDeprecated(): ?bool
    {
        return $this->ref()->deprecated;
    }

    public function isEmptyValueAllowed(): bool
    {
        return $this->hasEmptyValueAllowed() ? $this->ref()->allowEmptyValue : self::DEFAULT['allowEmptyValue'];
    }

    public function hasEmptyValueAllowed(): bool
    {
        return !is_null($this->ref()->allowEmptyValue);
    }

    public function getEmptyValueAllowed(): ?bool
    {
        return $this->ref()->allowEmptyValue;
    }

    public function hasSchema(): bool
    {
        return !is_null($this->ref()->schema);
    }

    public function getSchema(): ?Schema
    {
        return $this->ref()->schema;
    }

    protected function ref(): self
    {
        return $this;
    }

    private function excludeProperties(): array
    {
        return ['name'];
    }
}
