<?php declare(strict_types=1);

/**
 * @see https://spec.openapis.org/oas/v3.1.0#reference-object
 */
namespace OAS\Document;

class ParameterRef extends Parameter
{
    use Ref;

    public function __construct(string $_ref)
    {
        $this->_ref = $_ref;
    }

    public function getReference(): Parameter
    {
        $parameter = $this->find($this->_ref);

        if (!$parameter instanceof Parameter) {
            throw new \RuntimeException('is not a parameter instance');
        }

        return $parameter;
    }

    protected function ref(): Header
    {
        return $this->getReference();
    }
}
