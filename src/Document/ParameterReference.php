<?php declare(strict_types=1);

/**
 * @see https://spec.openapis.org/oas/v3.1.0#reference-object
 */
namespace OAS\Document;

use OAS\Utils\Node;

class ParameterReference extends Reference
{
    public function getReference(): Parameter
    {
        $parameter = parent::getReference();

        if (!$parameter instanceof Parameter) {
            throw new \RuntimeException();
        }

        return $parameter;
    }
}
