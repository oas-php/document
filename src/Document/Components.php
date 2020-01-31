<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Serializable;
use OAS\Schema;
use OAS\Utils\Node;
use function iter\all;
use function iter\func\operator;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#components-object
 */
final class Components extends Node implements \JsonSerializable
{
    use Serializable;

    /** @var \OAS\Schema[]|null */
    protected ?array $schemas;
    /** @var \OAS\Document\Response[]|null */
    protected ?array $responses;
    /** @var \OAS\Document\Parameter[]|null */
    protected ?array $parameters;
    /** @var \OAS\Document\Example[]|null */
    protected ?array $examples;
    /** @var \OAS\Document\RequestBody[]|null */
    protected ?array $requestBodies;
    /** @var \OAS\Document\Header[]|null */
    protected ?array $headers;
    /** @var \OAS\Document\SecurityScheme[]|null */
    protected ?array $securitySchemes;
    /** @var \OAS\Document\Link[]|null */
    protected ?array $links;
    /** @var \OAS\Document\Callback[]|null */
    protected ?array $callbacks;
    /** @var \OAS\Document\Path[]|null */
    protected ?array $pathsItems;

    /**
     * @param \OAS\Schema[]|null $schemas
     * @param \OAS\Document\Response[]|null $responses
     * @param \OAS\Document\Parameter[]|null $parameters
     * @param \OAS\Document\Example[]|null $examples
     * @param \OAS\Document\RequestBody[]|null $requestBodies
     * @param \OAS\Document\Header[]|null $headers
     * @param \OAS\Document\SecurityScheme[]|null $securitySchemes
     * @param \OAS\Document\Link[]|null $links
     * @param \OAS\Document\Callback[]|null $callbacks
     * @param \OAS\Document\Path[]|null $paths
     */
    public function __construct(
        array $schemas = null,
        array $responses = null,
        array $parameters = null,
        array $examples = null,
        array $requestBodies = null,
        array $headers = null,
        array $securitySchemes = null,
        array $links = null,
        array $callbacks = null,
        array $paths = null
    ) {
        $this->setSchemas($schemas);
        $this->setResponses($responses);
        $this->setParameters($parameters);
        $this->setExamples($examples);
        $this->setRequestBodies($requestBodies);
        $this->setHeaders($headers);
        $this->setSecuritySchemes($securitySchemes);
        $this->setLinks($links);
        $this->setCallbacks($callbacks);
        $this->setPaths($paths);
    }

    private function setSchemas(?array $schemas): void
    {
        if (!all(operator('instanceof', Schema::class), $schemas)) {
            throw new \TypeError(
                'Parameter "schemas" must be of ?\OAS\Schema[] type'
            );
        }

        foreach ($schemas ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->schemas = $schemas;
    }

    public function hasSchemas(): bool
    {
        return !\is_null($this->schemas);
    }

    /**
     * @return \OAS\Schema[]|null
     */
    public function getSchemas(): ?array
    {
        return $this->schemas;
    }

    public function hasSchema(string $key): bool
    {
        return $this->hasSchemas() && array_key_exists($key, $this->schemas);
    }

    public function getSchema(string $key): ?Schema
    {
        return $this->hasSchema($key) ? $this->schemas[$key] : null;
    }

    private function setResponses(?array $responses): void
    {
        if (!all(operator('instanceof', Response::class), $responses ?? [])) {
            throw new \TypeError(
                'Parameter "responses" must be of ?\OAS\Document\Response[] type'
            );
        }

        foreach ($responses ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->responses = $responses;
    }

    public function hasResponses(): bool
    {
        return !\is_null($this->responses);
    }

    /**
     * @return \OAS\Document\Response[]|null
     */
    public function getResponses(): ?array
    {
        return $this->responses;
    }

    public function hasResponse(string $key): bool
    {
        return $this->hasResponses() && array_key_exists($key, $this->responses);
    }

    public function getResponse(string $key): ?Response
    {
        return $this->hasResponse($key) ? $this->responses[$key] : null;
    }

    private function setParameters(?array $parameters): void
    {
        if (!all(operator('instanceof', Parameter::class), $parameters ?? [])) {
            throw new \TypeError(
                'Parameter "parameters" must be of ?\OAS\Document\Parameter[] type'
            );
        }

        foreach ($parameters ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->parameters = $parameters;
    }

    public function hasParameters(): bool
    {
        return !\is_null($this->parameters);
    }

    /**
     * @return \OAS\Document\Parameter[]|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function hasParameter(string $key): bool
    {
        return $this->hasParameters() && array_key_exists($key, $this->parameters);
    }

    public function getParameter(string $key): ?Parameter
    {
        return $this->hasParameter($key) ? $this->parameters[$key] : null;
    }

    private function setExamples(?array $examples): void
    {
        if (!all(operator('instanceof', Example::class), $examples ?? [])) {
            throw new \TypeError(
                'Parameter "examples" must be of ?\OAS\Document\Example[] type'
            );
        }

        foreach ($examples ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->examples = $examples;
    }

    public function hasExamples(): bool
    {
        return !\is_null($this->examples);
    }

    /**
     * @return \OAS\Document\Example[]|null
     */
    public function getExamples(): ?array
    {
        return $this->examples;
    }

    public function hasExample(string $key): bool
    {
        return $this->hasExamples() && \array_key_exists($key, $this->examples);
    }

    public function getExample(string $key): ?Example
    {
        return $this->hasExample($key) ? $this->examples[$key] : null;
    }

    private function setRequestBodies(?array $requestBodies): void
    {
        if (!all(operator('instanceof', RequestBody::class), $requestBodies ?? [])) {
            throw new \TypeError(
                'Parameter "requestBodies" must be of ?\OAS\Document\RequestBody[] type'
            );
        }

        foreach ($requestBodies ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->requestBodies = $requestBodies;
    }

    public function hasRequestBodies(): bool
    {
        return !\is_null($this->requestBodies);
    }

    /**
     * @return \OAS\Document\RequestBody[]|null
     */
    public function getRequestBodies(): ?array
    {
        return $this->requestBodies;
    }

    public function hasRequestBody(string $key): bool
    {
        return $this->hasRequestBodies() && \array_key_exists($key, $this->requestBodies);
    }

    public function getRequestBody(string $key): ?Example
    {
        return $this->hasRequestBody($key) ? $this->requestBodies[$key] : null;
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

    public function hasSecuritySchemes(): bool
    {
        return !is_null($this->securitySchemes);
    }

    private function setSecuritySchemes(?array $securitySchemes): void
    {
        if (!all(operator('instanceof', SecurityScheme::class), $securitySchemes ?? [])) {
            throw new \TypeError(
                'Parameter "securitySchemes" must be of ?\OAS\Document\SecurityScheme[] type'
            );
        }

        foreach ($securitySchemes ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->securitySchemes = $securitySchemes;
    }

    /**
     * @return \OAS\Document\SecurityScheme[]|null
     */
    public function getSecuritySchemes(): ?array
    {
        return $this->securitySchemes;
    }

    public function hasSecurityScheme(string $key): bool
    {
        return $this->hasSecuritySchemes() && array_key_exists($key, $this->securitySchemes);
    }

    public function getSecurityScheme(string $key): ?SecurityScheme
    {
        return $this->hasSecurityScheme($key) ? $this->securitySchemes[$key] : null;
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

    /**
     * @return \OAS\Document\Link[]|null
     */
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

    private function setCallbacks(?array $callbacks): void
    {
        if (!all(operator('instanceof', Callback::class), $callbacks ?? [])) {
            throw new \TypeError(
                'Parameter "callbacks" must be of ?\OAS\Document\Callback[] type'
            );
        }

        foreach ($callbacks ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->callbacks = $callbacks;
    }

    public function hasCallbacks(): bool
    {
        return !is_null($this->callbacks);
    }

    /**
     * @return \OAS\Document\Callback[]|null
     */
    public function getCallbacks(): ?array
    {
        return $this->callbacks;
    }

    public function hasCallback(string $key): bool
    {
        return $this->hasCallbacks() && array_key_exists($key, $this->callbacks);
    }

    public function getCallback(string $key): ?Callback
    {
        return $this->hasCallback($key) ? $this->callbacks[$key] : null;
    }

    private function setPaths(?array $paths): void
    {
        if (!all(operator('instanceof', Path::class), $paths ?? [])) {
            throw new \TypeError(
                'Parameter "paths" must be of ?\OAS\Document\Path[] type'
            );
        }

        foreach ($paths ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->pathsItems = $paths;
    }

    public function hasPaths(): bool
    {
        return !\is_null($this->pathsItems);
    }

    /**
     * @return Path[]|null
     */
    public function getPathsItems(): ?array
    {
        return $this->pathsItems;
    }

    public function hasPath(string $key): bool
    {
        return $this->hasPaths() && \array_key_exists($key, $this->pathsItems);
    }

    public function getPath(string $key): ?Path
    {
        return $this->hasPath($key) ? $this->pathsItems[$key] : null;
    }
}
