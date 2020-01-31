<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Document\Error\RetrievalError;
use OAS\Schema;
use OAS\Utils\Serializable;
use OAS\Utils\Node;
use function iter\all;
use function iter\func\operator;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#response-object
 */
final class Response extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $description;

    /** @var Header[]|null */
    protected ?array $headers;

    /** @var MediaType[]|null */
    protected ?array $content;

    /** @var Link[]|null */
    protected ?array $links;

    /**
     * @param string $description
     * @param \OAS\Document\MediaType[]|null $content
     * @param \OAS\Document\Header[]|null $headers
     * @param \OAS\Document\Link[]|null $links
     */
    public function __construct(string $description, array $headers = null, array $content = null, array $links = null)
    {
        $this->description = $description;
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setLinks($links);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    private function setHeaders(?array $headers): void
    {
        if (!all(operator('instanceof', Header::class), $headers ?? [])) {
            throw new \TypeError(
                'Parameter "headers" must be of ?\OAS\Document\Header[] type'
            );
        }

        foreach ($headers ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->headers = $headers;
    }

    public function hasHeaders(): bool
    {
        return !is_null($this->headers);
    }

    /**
     * @return Header[]|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function hasHeader(string $key): bool
    {
        return $this->hasHeaders() && array_key_exists($key, $this->headers);
    }

    public function getHeader(string $key): ?Header
    {
        return $this->hasHeader($key) ? $this->headers[$key] : null;
    }

    private function setContent(?array $content): void
    {
        if (!all(operator('instanceof', MediaType::class), $content ?? [])) {
            throw new \TypeError(
                'Parameter "Content" must be of ?\OAS\Document\MediaType[] type'
            );
        }

        foreach ($content ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->content = $content;
    }

    public function hasContent(): bool
    {
        return !is_null($this->content);
    }

    /**
     * @return MediaType[]|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    public function hasMediaType(string $mediaType): bool
    {
        return $this->hasContent() && array_key_exists($mediaType, $this->content);
    }

    public function getMediaType(string $mediaType): ?MediaType
    {
        return $this->hasMediaType($mediaType) ? $this->content[$mediaType] : null;
    }

    private function setLinks(?array $links): void
    {
        if (!all(operator('instanceof', Link::class), $links ?? [])) {
            throw new \TypeError(
                'Parameter "links" must be of ?\OAS\Document\Link[] type'
            );
        }

        foreach ($links ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->links = $links;
    }

    public function hasLinks(): bool
    {
        return !is_null($this->links);
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function hasLink(string $key): bool
    {
        return $this->hasLinks() && array_key_exists($key, $this->links);
    }

    public function getLink(string $key): ?Link
    {
        return $this->hasLink($key) ? $this->links[$key] : null;
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
