<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Schema;
use OAS\Utils\Node;
use OAS\Utils\Serializable;
use function iter\all;
use function iter\func\operator;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#media-type-object
 */
final class MediaType extends Node implements \JsonSerializable
{
    use Serializable;

    protected ?Schema $schema;
    protected $example;
    /** @var Example[]|null */
    protected ?array $examples;
    /** @var Encoding[]|null  */
    protected ?array $encoding;

    /**
     * @param \OAS\Document\Example[]|null  $examples
     * @param \OAS\Document\Encoding[]|null $encoding
     */
    public function __construct(
        Schema $schema = null,
        $example = null,
        array $examples = null,
        array $encoding = null
    ) {
        $this->schema = $schema;
        !is_null($schema) && $this->__connect($schema, ['schema']);
        $this->example = $example;
        $this->setExamples($examples);
        $this->setEncoding($encoding);
    }

    public function hasSchema(): bool
    {
        return !is_null($this->schema);
    }

    public function getSchema(): ?Schema
    {
        return $this->schema;
    }

    public function hasExample(): bool
    {
        return !is_null($this->example);
    }

    public function getExample()
    {
        return $this->example;
    }

    private function setExamples(?array $examples): void
    {
        if (!all(operator('instanceof', Example::class), $examples ?? [])) {
            throw new \TypeError(
                'Parameter "examples" must be of ?\OAS\Document\Example[] type'
            );
        }

        foreach ($examples ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->examples = $examples;
    }

    public function hasExamples(): bool
    {
        return !is_null($this->examples);
    }

    public function getExamples()
    {
        return $this->examples;
    }

    private function setEncoding(?array $encoding): void
    {
        if (!all(operator('instanceof', Encoding::class), $encoding ?? [])) {
            throw new \TypeError(
                'Parameter "encoding" must be of ?\OAS\Document\Encoding[] type'
            );
        }

        foreach ($encoding ?? [] as $childNode) {
            $this->__connect($childNode);
        }

        $this->encoding = $encoding;
    }

    public function hasEncoding(): bool
    {
        return !\is_null($this->encoding);
    }

    /**
     * @return \OAS\Document\Encoding[]|null
     */
    public function getEncoding(): ?array
    {
        return $this->encoding;
    }
}
