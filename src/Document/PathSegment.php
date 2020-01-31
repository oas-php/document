<?php declare(strict_types=1);

namespace OAS\Document;

/**
 * @internal
 */
class PathSegment
{
    private string $name;
    private bool $isPlaceholder;
    private Path $path;
    private ?Parameter $parameter;

    public function __construct(string $name, bool $isPlaceholder, Path $path, Parameter $parameter = null)
    {
        $this->name = $name;
        $this->isPlaceholder = $isPlaceholder;
        $this->path = $path;
        $this->parameter = $parameter;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isPlaceholder(): bool
    {
        return $this->isPlaceholder;
    }

    public function getPath(): Path
    {
        return $this->path;
    }

    public function getParameter(): ?Parameter
    {
        return $this->parameter;
    }
}
