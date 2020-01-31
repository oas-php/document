<?php declare(strict_types=1);

namespace OAS\Document;

use JsonSerializable;
use OAS\Document\Error\RetrievalError;
use OAS\Schema;
use OAS\Utils\Serializable;
use OAS\Utils\Node;
use function iter\all;
use function iter\func\operator;

/**
 * @see https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.1.0.md#requestBodyObject
 */
final class RequestBody extends Node implements JsonSerializable
{
    use Serializable;

    private const DEFAULT = [
        'required' => false
    ];

    /** @var MediaType[] */
    protected array $content;
    protected ?string $description;
    protected ?bool $required;

    /**
     * @param \OAS\Document\MediaType[] $content
     */
    public function __construct(array $content, string $description = null, bool $required = null)
    {
        $this->setContent($content);
        $this->description = $description;
        $this->required = $required;
    }

    private function setContent(array $content): void
    {
        if (!all(operator('instanceof', MediaType::class), $content)) {
            throw new \TypeError(
                'Parameter "content" must be of ?\OAS\Document\MediaType[] type'
            );
        }

        foreach ($content as $contentTypeName => $contentType) {
            $this->__connect($contentType, ['content', $contentTypeName]);
        }

        $this->content = $content;
    }

    /**
     * @return MediaType[]
     */
    public function getContent(): array
    {
        return $this->content;
    }

    public function getContentTypes(): array
    {
        return array_keys($this->content);
    }

    public function hasDescription(): bool
    {
        return !is_null($this->description);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function hasRequired(): bool
    {
        return !is_null($this->required);
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function isRequired(): bool
    {
        return !is_null($this->required) ? $this->required : self::DEFAULT['required'];
    }

    public function getSchema(string $contentType): Schema
    {
        if (!array_key_exists($contentType, $this->content)) {
            throw new RetrievalError(
                [...$this->getRootPath(), 'content'],
                $contentType
            );
        }

        if (!$this->content[$contentType]->hasSchema()) {
            throw new RetrievalError(
                [...$this->getRootPath(), 'content', $contentType],
                'schema'
            );
        }

        return $this->content[$contentType]->getSchema();
    }
}
