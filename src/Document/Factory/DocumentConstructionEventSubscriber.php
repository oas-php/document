<?php declare(strict_types=1);

namespace OAS\Document\Factory;

use OAS\Document\Operation;
use OAS\Document\ParameterRef;
use OAS\Document\Path;
use OAS\OpenApiDocument;
use OAS\Utils\Constructor\Event\BeforeParamsResolution;
use OAS\Utils\Constructor\Event\BeforeParamWithTypeResolution;
use OAS\Utils\Constructor\SubscriberInterface;

class DocumentConstructionEventSubscriber implements SubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            BeforeParamsResolution::class => [
                [$this, 'supplementPathWithItsName'],
                [$this, 'supplementOperationWithItsType']
            ],
            BeforeParamWithTypeResolution::class => [
                [$this, 'handleRefs']
            ]
        ];
    }

    public function supplementPathWithItsName(BeforeParamsResolution $event): void
    {
        if (OpenApiDocument::class === $event->getReflection()->getName()) {
            $params = $event->getParams();

            foreach (array_keys($params['paths'] ?? []) as $pathName) {
                $params['paths'][$pathName]['name'] = $pathName;
            }

            $event->setParams($params);
        }
    }

    public function supplementOperationWithItsType(BeforeParamsResolution $event): void
    {
        if (Path::class === $event->getReflection()->getName()) {
            $params = $event->getParams();

            foreach (Operation::TYPES as $operationType) {
                if (array_key_exists($operationType, $params)) {
                    $params[$operationType]['type'] = $operationType;
                }
            }

            $event->setParams($params);
        }
    }

    public function handleRefs(BeforeParamWithTypeResolution $event): void
    {
        $type = $event->getType();

        if ('\OAS\Document\Parameter' == $type) {
            $parameters = $event->getValue();

            if (array_key_exists('$ref', $parameters)  ) {
                $event->setValue(
                    new ParameterRef($parameters['$ref'])
                );
            }
        }
    }
}
