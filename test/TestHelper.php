<?php declare(strict_types=1);

use OAS\Document\Factory\Factory;

trait TestHelper
{
    public function openApiDocumentProvider(): array
    {
        $factory = new Factory();

        return [
            [
                $factory->createFromPrimitives(
                    $this->getRawSpec()
                )
            ]
        ];
    }


    private function getRawSpec(): array
    {
        return [
            'openapi' => '3.0.0',
            'info' => [
                'version' => '1.0.0',
                'title' => 'Movie Library',
                'license' => [
                    'name' => 'MIT'
                ]
            ],
            'servers' => [
                ['url' => 'http://example.com']
            ],
            'paths' => [
                '/movies' => [
                    'get' => [
                        'summary' => 'List all movies',
                        'operationId' => 'listMovies',
                        'tags' => [
                            'movies'
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Paged collection of movies',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => [
                                                    'type' => 'array',
                                                    'items' => [
                                                        '$ref' => '#/components/schemas/Movie'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'components' => [
                'parameters' => [
                    'id' => [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true
                    ]
                ],
                'schemas' => [
                    'Movie' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => [
                                'type' => 'string'
                            ],
                            'genre' => [
                                'type' => 'string'
                            ],
                            'year' => [
                                'type' => 'integer'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
