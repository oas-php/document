<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Serializable;
use OAS\Utils\Node;
use function iter\all;
use function iter\func\operator;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#encoding-object
 */
final class Encoding extends Node implements \JsonSerializable
{
    use Serializable;

    protected ?string $contentType;
    protected ?array $headers;
    protected ?string $style;
    protected ?bool $explode;
    protected ?bool $allowReserved;

    /**
     * @param \OAS\Document\Header[]|null $headers
     */
    public function __construct(
        string $contentType = null,
        array $headers = null,
        string $style = null,
        bool $explode = null,
        bool $allowReserved = null
    ) {
        $this->contentType = $contentType;
        $this->setHeaders($headers);
        $this->style = $style;
        $this->explode = $explode;
        $this->allowReserved = $allowReserved;
    }

    public function hasContentType(): bool
    {
        return !\is_null($this->contentType);
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
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
        return !\is_null($this->headers);
    }

    /**
     * @return \OAS\Document\Header[]|null
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

    public function hasStyle(): bool
    {
        return !\is_null($this->style);
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

    public function isExplode(): ?bool
    {
        return $this->explode;
    }

    public function hasAllowReserved(): bool
    {
        return !is_null($this->allowReserved);
    }

    public function getAllowReserved(): ?bool
    {
        return $this->allowReserved;
    }

    public function isAllowReserved(): ?bool
    {
        return $this->allowReserved;
    }
}
