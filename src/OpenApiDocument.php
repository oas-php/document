<?php declare(strict_types=1);

namespace OAS;

use ArrayAccess;
use Biera\ArrayAccessor;
use JsonSerializable;
use OAS\Document\Components;
use OAS\Document\Error\RetrievalError;
use OAS\Document\ExternalDocumentation;
use OAS\Document\Info;
use OAS\Document\Operation;
use OAS\Document\Path;
use OAS\Document\SecurityRequirement;
use OAS\Document\Server;
use OAS\Document\Tag;
use OAS\Utils\Node;
use OAS\Utils\Serializable;
use RuntimeException;
use function iter\{all, search};
use function iter\func\operator;

/**
 * TODO:
 *  - refactor name to OpenApiSpecification
 *  - OAS\Utils\Node - let node to know what is the path from parent (recursively to root)
 */
class OpenApiDocument extends Node implements JsonSerializable, ArrayAccess
{
    use ArrayAccessor, Serializable;

    private string $openapi;
    private Info $info;
    /** @var \OAS\Document\Path[]|null  */
    private ?array $paths;
    /** @var \OAS\Document\Server[]|null  */
    private ?array $servers;
    private ?Components $components;
    /** @var \OAS\Document\SecurityRequirement[]|null */
    private ?array $security;
    /** @var \OAS\Document\Tag[]|null */
    private ?array $tags;
    private ?ExternalDocumentation $externalDocs;

    /**
     * @param string                                    $openapi
     * @param \OAS\Document\Info                        $info
     * @param \OAS\Document\Path[]                      $paths
     * @param \OAS\Document\Server[]|null               $servers
     * @param \OAS\Document\Components|null             $components
     * @param \OAS\Document\SecurityRequirement[]|null  $security
     * @param \OAS\Document\Tag[]|null                  $tags
     * @param \OAS\Document\ExternalDocumentation|null  $externalDocs
     */
    public function __construct(
        string $openapi,
        Info $info,
        array $paths,
        array $servers = null,
        Components $components = null,
        array $security = null,
        array $tags = null,
        ExternalDocumentation $externalDocs = null
    ) {
        $this->openapi = $openapi;
        $this->info = $info;
        $this->__connect($info, ['info']);
        $this->setPaths($paths);
        $this->setServers($servers);
        $this->components = $components;
        !is_null($components) && $this->__connect($components, ['components']);
        $this->setSecurity($security);
        $this->setTags($tags);
        $this->externalDocs = $externalDocs;
        !is_null($externalDocs) && $this->__connect($externalDocs, ['externalDocs']);
    }

    public function getOpenapi(): string
    {
        return $this->openapi;
    }

    public function getInfo(): Info
    {
        return $this->info;
    }

    /** @param Path[] $paths */
    private function setPaths(array $paths): void
    {
        if (!all(operator('instanceof', Path::class), $paths)) {
            throw new \TypeError(
                'Parameter "paths" must be of ?\OAS\Document\Path[] type'
            );
        }

        foreach ($paths as $name => $path) {
            $this->__connect($path, ['paths', $name]);
        }

        $this->paths = $paths;
    }

    /**
     * @return Path[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    public function hasPath(string $name): bool
    {
        return \array_key_exists($name, $this->paths);
    }

    public function getPath(string $name): Path
    {
        if (!$this->hasPath($name)) {
            throw new RetrievalError($this->getRootPath(), $name, "Path {$name} does not exist.");
        }

        return $this->paths[$name];
    }

    private function setServers(?array $servers): void
    {
        if (!all(operator('instanceof', Server::class), $servers ?? [])) {
            throw new \TypeError(
                'Parameter "servers" must be of ?\OAS\Document\Server[] type'
            );
        }

        foreach ($servers ?? [] as $index => $childNode) {
            $this->__connect($childNode, ['servers', $index]);
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

    public function hasComponents(): bool
    {
        return !is_null($this->components);
    }

    public function getComponents(): ?Components
    {
        return $this->components;
    }

    private function setSecurity(?array $security): void
    {
        if (!all(operator('instanceof', SecurityRequirement::class), $security ?? [])) {
            throw new \TypeError(
                'Parameter "security" must be of ?\OAS\Document\Security[] type'
            );
        }

        foreach ($security ?? [] as $index => $childNode) {
            $this->__connect($childNode, ['security', $index]);
        }

        $this->security = $security;
    }

    public function hasSecurity(): bool
    {
        return !is_null($this->security);
    }

    /**
     * @return SecurityRequirement[]|null
     */
    public function getSecurity(): ?array
    {
        return $this->security;
    }

    private function setTags(?array $tags): void
    {
        if (!all(operator('instanceof', Tag::class), $tags ?? [])) {
            throw new \TypeError(
                'Parameter "tags" must be of ?\OAS\Document\Tag[] type'
            );
        }

        foreach ($tags ?? [] as $index => $tag) {
            $this->__connect($tag, ['tags', $index]);
        }

        $this->tags = $tags;
    }

    public function hasTags(): bool
    {
        return !is_null($this->tags);
    }

    /**
     * @return Tag[]|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function getExternalDocs(): ?ExternalDocumentation
    {
        return $this->externalDocs;
    }

    /**
     * @return Operation[]
     */
    public function getOperations(): array
    {
        return array_reduce(
            $this->getPaths(),
            function (array $operations, Path $path) {
                $pathOperations = $path->getOperations();

                if (!empty($pathOperations)) {
                    array_push($operations, ...array_values($pathOperations));
                }

                return $operations;
            },
            []
        );
    }

    public function hasOperation(string $type, string $path): bool
    {
        try {
            $path = $this->getPath($path);

            return  $path->hasOperation($type);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function getOperation(string $type, string $path): Operation
    {
        return $this->getPath($path)->getOperation($type);
    }

    public function getOperationById(string $id): Operation
    {
        $operation = search(
            fn (Operation $operation) => $id === $operation->getOperationId(),
            $this->getOperations()
        );

        if (is_null($operation)) {
            throw new \DomainException("Operation with {$id} id does not exist");
        }

        return $operation;
    }

    public function findOperation(string $type, string $compiledPath): ?Operation
    {
        /** @var Path|null $path */
        $path = search(
            fn (Path $path) => $path->matches($type, $compiledPath), $this->getPaths()
        );

        return !is_null($path) && $path->hasOperation($type)
            ? $path->getOperation($type)
            : null;
    }

    public function getRequestBodySchema(string $path, string $operation, string $contentType): Schema
    {
        return $this->getOperation($operation, $path)->getRequestBodySchema($contentType);
    }

    public function findRequestBodySchema(string $path, string $operation, string $contentType): ?Schema
    {
        try {
            $schema = $this['paths'][$path][$operation]['requestBody']['content'][$contentType]['schema'];
        } catch (RuntimeException $exception) {
            $schema = null;
        }

        return $schema;
    }

    public function get(string $path)
    {
        return $this->retrieve($path);
    }
}
