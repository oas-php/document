<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Schema;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#parameter-object
 */
class Parameter extends Header implements \JsonSerializable
{
    use Serializable;

    public const LOCATION_QUERY = 'query';
    public const LOCATION_PATH = 'path';
    public const LOCATION_COOKIE = 'cookie';
    public const LOCATION_HEADER = 'header';
    public const LOCATIONS = [
        self::LOCATION_QUERY,
        self::LOCATION_PATH,
        self::LOCATION_COOKIE,
        self::LOCATION_HEADER
    ];

    protected string $in;

    public function __construct(
        string $in,
        string $name,
        bool $required,
        string $description = null,
        bool $deprecated = null,
        bool $allowEmptyValue = null,
        string $style = null,
        bool $explode = null,
        bool $allowReserved = null,
        Schema $schema = null,
        $example = null,
        array $examples = null,
        array $content = null
    ) {
        parent::__construct(
            $name,
            $required,
            $description,
            $deprecated,
            $allowEmptyValue,
            $style,
            $explode,
            $allowReserved,
            $schema,
            $example,
            $examples,
            $content
        );

        // TODO
        //  - set up exception class & message
        if (self::LOCATION_PATH === $in && !$required) {
            throw new \InvalidArgumentException(
                "Parameter 'required' must be set to 'true' when parameter 'in' value is 'query'"
            );
        }

        // TODO
        //  - set up exception class
        if (self::LOCATION_QUERY !== $in && !is_null($allowEmptyValue)) {
            throw new \InvalidArgumentException(
                "Parameter 'allowEmptyValue' is only applicable when 'in' parameters equals 'query'"
            );
        }

        $this->in = $in;
    }

    public function getIn(): string
    {
        return $this->ref()->in;
    }
}
