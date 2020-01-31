<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Document\Error\RetrievalError;
use OAS\Schema;
use OAS\Utils\Node;
use OAS\Utils\Serializable;
use OAS\Validator;
use OAS\Validator\SchemaConformanceFailure;
use function Biera\pathSegments;
use function iter\all;
use function iter\func\operator;
use function iter\search;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#path-item-object
 */
final class Path extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $name;
    protected ?string $summary;
    protected ?string $description;
    /** @var Server[]|null */
    protected ?array $servers;
    /** @var Parameter[]|null */
    protected ?array $parameters;
    protected ?Operation $get;
    protected ?Operation $put;
    protected ?Operation $post;
    protected ?Operation $patch;
    protected ?Operation $delete;
    protected ?Operation $options;
    protected ?Operation $head;
    protected ?Operation $trace;

    /**
     * @param string $name
     * @param string|null $summary
     * @param string|null $description
     * @param \OAS\Document\Server[]|null $servers
     * @param \OAS\Document\Parameter[]|null $parameters
     * @param \OAS\Document\Operation|null $get
     * @param \OAS\Document\Operation|null $post
     * @param \OAS\Document\Operation|null $put
     * @param \OAS\Document\Operation|null $patch
     * @param \OAS\Document\Operation|null $delete
     * @param \OAS\Document\Operation|null $options
     * @param \OAS\Document\Operation|null $head
     * @param \OAS\Document\Operation|null $trace
     */
    public function __construct(
        string $name,
        string $summary = null,
        string $description = null,
        array $servers = null,
        array $parameters = null,
        ?Operation $get =  null,
        ?Operation $post =  null,
        ?Operation $put =  null,
        ?Operation $patch =  null,
        ?Operation $delete =  null,
        ?Operation $options =  null,
        ?Operation $head =  null,
        ?Operation $trace =  null
    ) {
        $this->name = $name;
        $this->summary = $summary;
        $this->description = $description;
        $this->setServers($servers);
        $this->setParameters($parameters);
        $this->get = $get;
        $this->post = $post;
        $this->put = $put;
        $this->patch = $patch;
        $this->delete = $delete;
        $this->options = $options;
        $this->head = $head;
        $this->trace = $trace;
        !is_null($put) && $this->__connect($put, ['put']);
        !is_null($patch) && $this->__connect($patch, ['patch']);
        !is_null($delete) && $this->__connect($delete, ['delete']);
        !is_null($options) && $this->__connect($options, ['options']);
        !is_null($head) && $this->__connect($head, ['head']);
        !is_null($trace) && $this->__connect($trace, ['trace']);
        !is_null($get) && $this->__connect($get, ['get']);
        !is_null($post) && $this->__connect($post, ['post']);
    }

    public function getName(): string
    {
        return $this->name;
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

    private function setServers(?array $servers): void
    {
        if (!all(operator('instanceof', Server::class), $servers ?? [])) {
            throw new \TypeError(
                'Parameter "servers" must be of ?\OAS\Document\Server[] type'
            );
        }

        foreach ($servers ?? [] as $index => $server) {
            $this->__connect($server, ['servers', $index]);
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

    private function setParameters(?array $parameters): void
    {
        if (!all(operator('instanceof', Parameter::class), $parameters ?? [])) {
            throw new \TypeError(
                'Parameter "parameters" must be of ?\OAS\Document\Parameter[] type'
            );
        }

        foreach ($parameters ?? [] as $index => $parameter) {
            $this->__connect($parameter, ['parameters', $index]);
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
            $this->findParameter($name)
        );
    }

    public function getParameter(string $name): Parameter
    {
        $parameter = $this->findParameter($name);

        if (is_null($parameter)) {
            throw new \DomainException("Path {$this->name} does not have \"{$name}\" parameter");
        }

        return $parameter;
    }

    public function findParameter(string $name): ?Parameter
    {
        return search(
            fn (Parameter $parameter) => $name == $parameter->getName(),
            $this->parameters ?? []
        );
    }

    public function hasPlaceholders(): bool
    {
        return !empty($this->getPlaceholdersNames());
    }

    public function getPlaceholdersNames(bool $strip = false): array
    {
        if (1 >= preg_match_all('/\{(\w+)\}/', $this->name, $matches)) {
            return $strip ? $matches[1] : $matches[0];
        }

        return [];
    }

    /**
     * @param string $type operation method, e.g: GET, POST or DELETE
     * @param string $compiledPath a path with values in place of placeholders, e.g: /movies/cd31665c-e664-46f6-810c-7be9cb1692dc
     * @return bool
     */
    public function matches(string $type, string $compiledPath): bool
    {
        if (!$this->hasOperation($type)) {
            return  false;
        }

        if (!$this->hasPlaceholders()) {
            return $compiledPath == $this->getName();
        }

        $pairs = zip($this->getSegments($type), pathSegments($compiledPath, '/'));
        $validator = new Validator();

        /** @var PathSegment $pathSegment */
        foreach ($pairs as [$pathSegment, $compiledPathSegment]) {
            // compiled path is at least one segment longer
            if (is_null($pathSegment) && !is_null($compiledPathSegment)) {
                return false;
            }

            if (!is_null($pathSegment) && is_null($compiledPathSegment)) {
                if (!$pathSegment->isPlaceholder()) {
                    return false;
                }

                $parameter = $pathSegment->getParameter();

                // required placeholder is missing
                if ($parameter->isRequired()) {
                    return false;
                }
            }

            if (!is_null($pathSegment) && !is_null($compiledPathSegment)) {
                if ($pathSegment->isPlaceholder()) {
                    $parameter = $pathSegment->getParameter();

                    if ($parameter->hasSchema()) {
                        $schema = $parameter->getSchema();

                        $matchesSchema = $this->isValid($validator, $compiledPathSegment, $schema)
                        ||
                        (
                            is_numeric($compiledPathSegment) &&
                            (
                                $this->isValid($validator, (int) $compiledPathSegment, $schema)
                                ||
                                $this->isValid($validator, (float) $compiledPathSegment, $schema)
                            )
                        );

                        if (!$matchesSchema) {
                            return false;
                        }
                    }
                } else {
                    if ($compiledPathSegment != $pathSegment->getName()) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @return Operation[]
     */
    public function getOperations(): array
    {
        return array_reduce(
            Operation::TYPES,
            function (array $operations, $operationType) {
                try {
                    array_push($operations, $this->getOperation($operationType));
                } catch (RetrievalError $exception) {
                }

                return $operations;
            },
            []
        );
    }

    public function getOperation(string $type): Operation
    {
        if (!$this->hasOperation($type)) {
            throw new RetrievalError($this->getRootPath(), $type);
        }

        $normalized = strtolower($type);

        return $this->{$normalized};
    }

    public function hasOperation(string $type): bool
    {
        $normalized = strtolower($type);

        return in_array($normalized, Operation::TYPES) && !is_null($this->{$normalized});
    }

    private function isValid(Validator $validator, $value, Schema $schema): bool
    {
        try {
            $validator->validate($value, $schema);
            return true;
        } catch (SchemaConformanceFailure $exception) {
            return false;
        }
    }

    /**
     * @return PathSegment[]
     */
    private function getSegments(string $type = null): array
    {
        return array_map(
            function (string $pathSegment) use ($type) {
                $isPlaceholder = (1 === preg_match('/\{(\w+)\}/', $pathSegment, $matches));

                if ($isPlaceholder) {
                    $pathSegment = $matches[1];

                    if (
                        !is_null($type) &&
                        $this->hasOperation($type) &&
                        $this->getOperation($type)->hasParameter($pathSegment)
                    ) {
                        $parameter = $this->getOperation($type)->getParameter($pathSegment);
                    } else {
                        $parameter = $this->getParameter($pathSegment);
                    }

                    return new PathSegment($pathSegment, true, $this, $parameter);
                }

                return new PathSegment($pathSegment, false, $this);
            },
            pathSegments($this->name, '/')
        );
    }

    private function excludeProperties(): array
    {
        return ['name'];
    }
}
