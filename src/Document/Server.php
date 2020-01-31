<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;
use function iter\all;
use function iter\func\operator;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#serverObject
 */
final class Server extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $url;

    protected ?string $description;

    /** @var ServerVariable[]|null  */
    protected ?array $variables;

    /**
     * @param \OAS\Document\ServerVariable[] $variables
     */
    public function __construct(string $url, string $description = null, array $variables = null)
    {
        $this->url = $url;
        $this->description = $description;
        $this->setVariables($variables);
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

    public function hasVariables(): bool
    {
        return !is_null($this->variables);
    }

    private function setVariables(?array $variables): void
    {
        if (!all(operator('instanceof', ServerVariable::class), $variables ?? [])) {
            throw new \TypeError(
                'Parameter "variables" must be of ?\OAS\Document\ServerVariable[] type'
            );
        }

        foreach ($variables ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->variables = $variables;
    }

    /**
     * @return ServerVariable[]|null
     */
    public function getVariables(): ?array
    {
        return $this->variables;
    }

    public function hasVariable(string $key): bool
    {
        return $this->hasVariables() && array_key_exists($key, $this->variables);
    }

    public function getVariable(string $key): ?ServerVariable
    {
        return $this->hasVariable($key) ? $this->variables[$key] : null;
    }
}
