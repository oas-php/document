<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Serializable;
use OAS\Utils\Node;

/**
 * TODO: implement
 *
 * @see https://spec.openapis.org/oas/v3.1.0#callback-object
 */
final class Callback extends Node implements \JsonSerializable
{
    use Serializable;
}
