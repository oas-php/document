<?php declare(strict_types=1);

namespace OAS\Document\Factory;

use OAS\OpenApiDocument;
use OAS\Resolver\Resolver;
use OAS\Schema\Factory\SchemaConstructionEventsSubscriber;
use OAS\Utils\Constructor\Constructor;
use OAS\Utils\Constructor\Dispatcher;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter;

class Dumper
{
    private ?Resolver $resolver;
    private Constructor $constructor;

    public function __construct(Resolver $resolver = null)
    {
        $this->resolver = $resolver;
        $dispatcher = new Dispatcher();

        $dispatcher->subscribe(
            new SchemaConstructionEventsSubscriber(false)
        );

        $dispatcher->subscribe(
            new DocumentConstructionEventSubscriber()
        );

        $this->constructor = new Constructor($dispatcher);
    }

    public function dumpFromPrimitives($primitives, string $path): void
    {
        $prettyPrinter = new PrettyPrinter\Standard();

        file_put_contents(
            $path,
            $prettyPrinter->prettyPrintFile(
                [
                    new Return_(
                        $this->getAstFromPrimitives($primitives)
                    )
                ]
            )
        );
    }

    /**
     * @param \stdClass|array|bool $primitives
     * @return Expr
     */
    public function getAstFromPrimitives($primitives): Expr
    {
        if ($primitives instanceof \stdClass) {
            $primitives = (array) $primitives;
        }

        if (!is_array($primitives)) {
            throw new \TypeError(
                'Parameter "primitives" must be of array|\stdClass type'
            );
        }

        return $this->constructor->getAST(
            OpenApiDocument::class,
            $this->resolver
                // TODO: check if it can be resolved to \stdClass
                ? $this->resolver
                    ->resolveDecoded($primitives)
                    ->denormalize(true)
                : $primitives
        );
    }
}
