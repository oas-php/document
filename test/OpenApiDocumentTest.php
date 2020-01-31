<?php

use OAS\Document\Operation;
use OAS\OpenApiDocument;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/TestHelper.php';

class OpenApiDocumentTest extends TestCase
{
    use TestHelper;

    /**
     * @test
     * @dataProvider openApiDocumentProvider
     */
    public function itGetsOperationById(OpenApiDocument $apiDocument): void
    {
        $this->assertEquals(
            'GET /movies',
            (string) $apiDocument->getOperationById('listMovies')
        );
    }

    /**
     * @test
     * @dataProvider openApiDocumentProvider
     */
    public function itGetsOperationByTypeAndPath(OpenApiDocument $apiDocument): void
    {
        $this->assertEquals(
            'GET /movies',
            (string) $apiDocument->getOperation('GET', '/movies')
        );
    }

    /**
     * @test
     * @dataProvider openApiDocumentProvider
     */
    public function itGetAllOperations(OpenApiDocument $apiDocument): void
    {
        $expectedOperations = [
            'GET /movies'
        ];

        $this->assertEquals(
            $expectedOperations,
            array_map(
                fn (Operation $operation) => (string) $operation,
                $apiDocument->getOperations()
            )
        );
    }

    /**
     * @test
     * @dataProvider openApiDocumentProvider
     */
    public function itSerializesToJSON(OpenApiDocument $spec): void
    {
        $rawSpec = $this->getRawSpec();

        $this->assertJsonStringEqualsJsonString(
            json_encode($rawSpec),
            json_encode($spec)
        );
    }
}
