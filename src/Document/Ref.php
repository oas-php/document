<?php declare(strict_types=1);

namespace OAS\Document;

trait Ref
{
    protected string $_ref;
    protected ?string $summary;
    protected ?string $description;
}
