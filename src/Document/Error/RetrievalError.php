<?php declare(strict_types=1);

namespace OAS\Document\Error;

use RuntimeException;
use Throwable;

class RetrievalError extends RuntimeException
{
    private array $path;
    private string $node;

    public function __construct(array $path, string $node, string $message = null, $code = 0, Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = sprintf(
                'Node "%s" does not exist at "%s" path.',
                $node,
                join(' -> ', $path)
            );
        }

        $this->path = $path;
        $this->node = $node;

        parent::__construct($message, $code, $previous);
    }

    public function getPath(): array
    {
        return $this->path;
    }

    public function getNode(): string
    {
        return $this->node;
    }
}