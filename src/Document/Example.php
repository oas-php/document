<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Serializable;
use OAS\Utils\Node;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#example-object
 */
final class Example extends Node implements \JsonSerializable
{
    use Serializable;

    protected ?string $summary;
    protected ?string $description;
    protected $value;
    protected ?string $externalValue;

    public function __construct(string $summary = null, string $description = null, $value = null, string $externalValue = null)
    {
        if (!is_null($value) && !is_null($externalValue)) {
            throw new \InvalidArgumentException(
                "The 'value' and 'externalValue' parameters are mutually exclusive: can not be both set"
            );
        }

        $this->summary = $summary;
        $this->description = $description;
        $this->value = $value;
        $this->externalValue = $externalValue;
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

    public function hasValue(): bool
    {
        return !is_null($this->value);
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function hasExternalValue(): bool
    {
        return !is_null($this->externalValue);
    }

    public function getExternalValue(): ?string
    {
        return $this->externalValue;
    }
}
