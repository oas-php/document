<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Document\Error\RetrievalError;
use OAS\Schema;
use OAS\Utils\Serializable;
use OAS\Utils\Node;
use function iter\all;
use function iter\func\operator;
use function iter\search;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#operation-object
 */
final class Operation extends Node implements \JsonSerializable, \ArrayAccess
{
    use Serializable;

    public const TYPE_GET = 'get';
    public const TYPE_POST = 'post';
    public const TYPE_PUT = 'put';
    public const TYPE_PATCH = 'patch';
    public const TYPE_DELETE = 'delete';
    public const TYPE_OPTIONS = 'options';
    public const TYPE_HEAD = 'head';
    public const TYPE_TRACE = 'trace';

    public const TYPES = [
        self::TYPE_GET,
        self::TYPE_POST,
        self::TYPE_DELETE,
        self::TYPE_PUT,
        self::TYPE_PATCH,
        self::TYPE_OPTIONS,
        self::TYPE_HEAD,
        self::TYPE_TRACE
    ];

    private const DEFAULTS = [
        'deprecated' => false
    ];

    protected string $type;
    protected ?array $tags;
    protected ?string $summary;
    protected ?string $description;
    protected ?ExternalDocumentation $externalDocs;
    /** @var Response[]|null  */
    protected ?array $responses;
    /** @var Parameter[]|null  */
    protected ?array $parameters;
    protected ?RequestBody $requestBody;
    protected ?string $operationId;
    /** @var Callback[]|null */
    protected ?array $callbacks;
    protected ?bool $deprecated;
    /** @var SecurityRequirement[]|null  */
    protected ?array $security;
    /** @var Server[]|null  */
    protected ?array $servers;

    /**
     * @param string $type
     * @param array|null $tags
     * @param string|null $summary
     * @param string|null $description
     * @param ExternalDocumentation|null $externalDocs
     * @param \OAS\Document\Response[]|null $responses
     * @param \OAS\Document\Parameter[]|null $parameters
     * @param \OAS\Document\RequestBody|null $requestBody
     * @param string|null $operationId
     * @param \OAS\Document\Callback[]|null $callbacks
     * @param bool|null $deprecated
     * @param \OAS\Document\SecurityRequirement[]|null $security
     * @param \OAS\Document\Server[]|null $servers
     */
    public function __construct(
        string $type,
        array $tags = null,
        string $summary = null,
        string $description = null,
        ExternalDocumentation $externalDocs = null,
        array $responses = null,
        array $parameters = null,
        RequestBody $requestBody = null,
        string $operationId = null,
        array $callbacks = null,
        bool $deprecated = null,
        array $security = null,
        array $servers = null
    ) {
        if (!in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Operation type "{$type}" is not supported (supported types are: %s)',
                    join(', ', self::TYPES)
                )
            );
        }

        $this->type = $type;
        $this->tags = $tags;
        $this->summary = $summary;
        $this->description = $description;
        $this->externalDocs = $externalDocs;
        $this->setResponses($responses);
        $this->setParameters($parameters);
        $this->requestBody = $requestBody;
        $this->operationId = $operationId;
        $this->setCallbacks($callbacks);
        $this->deprecated = $deprecated;
        $this->setSecurity($security);
        $this->setServers($servers);
        !is_null($externalDocs) && $this->__connect($externalDocs, ['externalDocs']);
        !is_null($requestBody) && $this->__connect($requestBody, ['requestBody']);
    }

    public function getPath(): Path
    {
        $path = $this->find('..');

        if (is_null($path)) {
            throw new \LogicException('Operation is out of path context');
        }

        return $path;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isType(string $type): bool
    {
        return strtolower($type) === $this->type;
    }

    public function hasTags(): bool
    {
        return !is_null($this->tags);
    }

    public function getTags(): ?array
    {
        return $this->tags;
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

    public function hasExternalDocs(): bool
    {
        return !is_null($this->externalDocs);
    }

    public function getExternalDocs(): ?ExternalDocumentation
    {
        return $this->externalDocs;
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
        return !is_null($this->responses);
    }

    /**
     * @return Response[]|null
     */
    public function getResponses(): ?array
    {
        return $this->responses;
    }

    public function hasDefaultResponse(): bool
    {
        return $this->hasResponses() && array_key_exists('default', $this->responses);
    }

    public function getDefaultResponse(): ?Response
    {
        return $this->hasDefaultResponse() ? $this->responses['default'] : null;
    }

    public function hasResponse(int $code): bool
    {
        return $this->hasResponses() && array_key_exists($code, $this->responses);
    }

    public function getResponse(int $code): ?Response
    {
        return $this->responses[$code] ?? null;
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
        return !is_null($this->parameters);
    }

    /**
     * @return Parameter[]|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function hasParameter(string $name): bool
    {
        return !is_null(
            search(
                fn(Parameter $parameter) => $name === $parameter->getName(),
                $this->parameters ?? []
            )
        );
    }

    public function getParameter(string $name): Parameter
    {
        $parameter = search(
            fn(Parameter $parameter) => $name === $parameter->getName(),
            $this->parameters ?? []
        );

        if (is_null($parameter)) {
            throw new \DomainException(
                "Parameter with \"{$name}\" name does not exist"
            );
        }

        return $parameter;
    }

    public function hasRequestBody(): bool
    {
        return !is_null($this->requestBody);
    }

    public function getRequestBody(): ?RequestBody
    {
        return $this->requestBody;
    }

    public function hasOperationId(): bool
    {
        return !is_null($this->operationId);
    }

    public function getOperationId(): ?string
    {
        return $this->operationId;
    }

    public function hasCallbacks(): bool
    {
        return !is_null($this->callbacks);
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

    public function hasDeprecated(): bool
    {
        return !is_null($this->deprecated);
    }

    public function getDeprecated(): ?bool
    {
        return $this->deprecated;
    }

    public function isDeprecated(): bool
    {
        return $this->hasDeprecated() ? $this->deprecated : self::DEFAULTS['deprecated'];
    }

    private function setServers(?array $servers): void
    {
        if (!all(operator('instanceof', Server::class), $servers ?? [])) {
            throw new \TypeError(
                'Parameter "servers" must be of ?\OAS\Document\Server[] type'
            );
        }

        foreach ($servers ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->servers = $servers;
    }

    public function hasServers(): bool
    {
        return !is_null($this->servers);
    }

    /**
     * @return Server[]|null
     */
    public function getServers(): ?array
    {
        return $this->servers;
    }

    private function setSecurity(?array $security): void
    {
        if (!all(operator('instanceof', SecurityRequirement::class), $security ?? [])) {
            throw new \TypeError(
                'Parameter "security" must be of ?\OAS\Document\SecurityRequirement[] type'
            );
        }

        foreach ($security ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->security = $security;
    }

    public function hasSecurity(): bool
    {
        return !is_null($this->security);
    }

    /**
     * @return \OAS\Document\SecurityRequirement[]|null
     */
    public function getSecurity(): ?array
    {
        return $this->security;
    }

    public function hasRequestBodySchema(string $contentType): bool
    {
        return !is_null($this->findRequestBodySchema($contentType));
    }

    public function getRequestBodySchema(string $contentType): Schema
    {
        if (!$this->hasRequestBody()) {
            throw new RetrievalError($this->getRootPath(), 'requestBody');
        }

        return $this->requestBody->getSchema($contentType);
    }

    public function findRequestBodySchema(string $contentType): ?Schema
    {
        try {
            $schema = $this['requestBody']['content'][$contentType]['schema'];
        } catch (\RuntimeException $exception) {
            $schema = null;
        }

        return $schema;
    }

    protected function excludeProperties(): array
    {
        return ['type'];
    }

    public function __toString()
    {
        $path = $this->getPath();

        return sprintf('%s %s', strtoupper($this->type), $path->getName());
    }
}
