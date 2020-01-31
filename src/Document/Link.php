<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#link-object
 */
final class Link extends Node implements \JsonSerializable
{
    use Serializable;

    protected ?string $operationRef;

    protected ?string $operationId;

    protected ?array $parameters;

    protected $requestBody;

    protected ?string $description;

    protected ?Server $server;

    /**
     * TODO:
     *  - parameters
     *  - requestBody
     */
    public function __construct(
        string $operationRef = null,
        string $operationId = null,
        array $parameters = null,
        $requestBody = null,
        string $description = null,
        Server $server = null
    ) {
        $this->operationRef = $operationRef;
        $this->operationId = $operationId;
        $this->parameters = $parameters;
        $this->requestBody = $requestBody;
        $this->description = $description;
        $this->server = $server;
    }

    public function hasOperationRef(): bool
    {
        return !is_null($this->operationRef);
    }

    public function getOperationRef(): ?string
    {
        return $this->operationRef;
    }

    public function hasOperationId(): bool
    {
        return !\is_null($this->operationId);
    }

    public function getOperationId(): ?string
    {
        return $this->operationId;
    }

    public function hasParameters(): bool
    {
        return !\is_null($this->parameters);
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function hasRequestBody(): bool
    {
        return !\is_null($this->requestBody);
    }

    public function getRequestBody()
    {
        return $this->requestBody;
    }

    public function hasDescription(): bool
    {
        return !is_null($this->description);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function hasServer(): bool
    {
        return !\is_null($this->server);
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }
}
