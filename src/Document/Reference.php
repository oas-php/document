<?php declare(strict_types=1);

/**
 * @see https://spec.openapis.org/oas/v3.1.0#reference-object
 */
namespace OAS\Document;

use OAS\Utils\Node;

class Reference extends Node
{
    protected string $_ref;

    protected ?string $summary;

    protected ?string $description;

    public function __construct(string $_ref, ?string $summary, ?string $description)
    {
        $this->_ref = $_ref;
        $this->summary = $summary;
        $this->description = $description;
    }

    public function getRef(): string
    {
        return $this->_ref;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getReference(): Node
    {

    }
}
