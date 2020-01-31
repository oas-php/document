<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Serializable;
use OAS\Utils\Node;
use function iter\all;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#server-variable-object
 */
final class ServerVariable extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $default;
    protected array $enum;
    protected ?string $description;

    public function __construct(
        string $default,
        array $enum,
        string $description = null
    ) {
        $this->default = $default;
        $this->setEnum($enum);
        $this->description = $description;
    }

    public function getDefault(): string
    {
        return $this->default;
    }

    private function setEnum(array $enum): void
    {
        if (!all('is_string', $enum)) {
            throw new \TypeError(
                'Parameter "enum" must be of string[] type'
            );
        }

        if (empty($enum)) {
            throw new \InvalidArgumentException(
                'Parameter "enum" must not be empty'
            );
        }

        $this->enum = $enum;
    }

    public function getEnum(): ?array
    {
        return $this->enum;
    }

    public function hasDescription(): bool
    {
        return !\is_null($this->description);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
