<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * TODO: implement
 *
 * @see https://spec.openapis.org/oas/v3.1.0#security-requirement-object
 */
final class SecurityRequirement extends Node implements \JsonSerializable
{
    use Serializable;

    public function __construct() {}
}
