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
        'allowEmptyValue' => false,
        'explode' => false,
        'allowReserved' => false,
    ];

    protected string $name;
    protected bool $required;
    protected ?string $description;
    protected ?bool $deprecated;
    protected ?bool $allowEmptyValue;
    protected ?string $style;
    protected ?bool $explode;
    protected ?bool $allowReserved;
    protected ?Schema $schema;
    protected $example;
    /** @var Example[]|null */
    protected ?array $examples;
    /** @var MediaType[]|null */
    protected ?array $content;

    /**
     * @param \OAS\Document\Example[]|null  $examples
     * @param \OAS\Document\MediaType[] $content
     */
    public function __construct(
        string $name,
        bool $required,
        string $description = null,
        bool $deprecated = null,
        bool $allowEmptyValue = null,
        string $style = null,
        bool $explode = null,
        bool $allowReserved = null,
        Schema $schema = null,
        $example = null,
        array $examples = null,
        array $content = null
    ) {
        $this->name = $name;
        $this->required = $required;
        $this->description = $description;
        $this->deprecated = $deprecated;
        $this->allowEmptyValue = $allowEmptyValue;
        $this->style = $style;
        $this->explode = $explode;
        $this->allowReserved = $allowReserved;
        $this->schema = $schema;
        $this->example = $example;
        $this->examples = $examples;
        $this->content = $content;
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

    public function hasAllowEmptyValue(): bool
    {
        return !is_null($this->ref()->allowEmptyValue);
    }

    public function getAllowEmptyValue(): ?bool
    {
        return $this->ref()->allowEmptyValue;
    }

    public function isAllowEmptyValue(): bool
    {
        return $this->hasAllowEmptyValue() ? $this->getAllowEmptyValue() : self::DEFAULT['allowEmptyValue'];
    }

    public function hasStyle(): bool
    {
        return !is_null($this->style);
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function hasExplode(): bool
    {
        return !is_null($this->explode);
    }

    public function getExplode(): ?bool
    {
        return $this->explode;
    }

    public function isExplode(): bool
    {
        return $this->explode && self::DEFAULT['explode'];
    }

    public function hasAllowReserved(): bool
    {
        return !is_null($this->allowReserved);
    }

    public function getAllowReserved(): ?bool
    {
        return $this->allowReserved;
    }

    public function isAllowReserved(): bool
    {
        return $this->allowReserved ?? self::DEFAULT['allowReserved'];
    }

    public function hasSchema(): bool
    {
        return !is_null($this->ref()->schema);
    }

    public function getSchema(): ?Schema
    {
        return $this->ref()->schema;
    }

    public function hasExample(): bool
    {
        return !is_null($this->example);
    }

    public function getExample()
    {
        return $this->example;
    }

    public function hasExamples(): bool
    {
        return !is_null($this->examples);
    }

    public function getExamples(): ?array
    {
        return $this->examples;
    }

    public function hasContent(): bool
    {
        return !is_null($this->content);
    }

    /**
     * @return MediaType[]
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    protected function ref(): self
    {
        return $this;
    }

    protected function excludeProperties(): array
    {
        return ['name'];
    }
}
