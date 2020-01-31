<?php declare(strict_types=1);

namespace OAS\Document\Factory;

use OAS\Utils\Constructor\Constructor;
use OAS\Utils\Constructor\Dispatcher;
use OAS\OpenApiDocument;
use OAS\Resolver\Resolver;
use OAS\Schema\Factory\SchemaConstructionEventsSubscriber;

class Factory
{
    private ?Resolver $resolver;
    private Constructor $constructor;

    public function __construct(Resolver $resolver = null)
    {
        $this->resolver = $resolver;
        $dispatcher = new Dispatcher();

        $dispatcher->subscribe(
            new SchemaConstructionEventsSubscriber()
        );

        $dispatcher->subscribe(
            new DocumentConstructionEventSubscriber()
        );

        $this->constructor = new Constructor($dispatcher);
    }

    /**
     * @param \stdClass|array|bool $primitives
     * @return OpenApiDocument|object
     */
    public function createFromPrimitives($primitives): OpenApiDocument
    {
        if ($primitives instanceof \stdClass) {
            $primitives = (array) $primitives;
        }

        if (!is_array($primitives)) {
            throw new \TypeError(
                'Parameter "params" must be of bool|array|\stdClass type'
            );
        }

        return $this->constructor->construct(
            OpenApiDocument::class,
            $this->resolver
                ? $this->resolver
                    ->resolveDecoded($primitives)
                    ->denormalize(true)
                : $primitives
        );
    }

    /**
     * @param string $uri
     * @return OpenApiDocument|object
     */
    public function createFromUri(string $uri): OpenApiDocument
    {
        if (is_null($this->resolver)) {
            throw new \LogicException(
                'Resolver must be set'
            );
        }

        $resolved = $this->resolver->resolve($uri)->denormalize(true);

        if ($resolved instanceof \stdClass) {
            $resolved = (array) $resolved;
        }

        return $this->constructor->construct(OpenApiDocument::class, $resolved);
    }
}
