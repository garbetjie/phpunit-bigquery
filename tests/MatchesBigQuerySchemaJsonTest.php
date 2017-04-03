<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests;

use Garbetjie\PHPUnit\BigQuery\MatchesBigQuerySchemaJson;
use PHPUnit\Framework\TestCase;

class MatchesBigQuerySchemaJsonTest extends TestCase
{
    /**
     * @var MatchesBigQuerySchemaJson
     */
    private $constraint;

    protected function setUp()
    {
        parent::setUp();

        $this->constraint = new MatchesBigQuerySchemaJson([
            [
                "mode" => "REQUIRED",
                "name" => "id",
                "type" => "STRING"
            ],
            [
                "mode" => "REQUIRED",
                "name" => "created",
                "type" => "TIMESTAMP"
            ],
            [
                "mode" => "REPEATED",
                "name" => "outgoing",
                "type" => "RECORD",
                "fields" => [
                    [
                        "mode" => "REQUIRED",
                        "name" => "id",
                        "type" => "FLOAT"
                    ],
                    [
                        "mode" => "REQUIRED",
                        "name" => "created",
                        "type" => "TIMESTAMP"
                    ],
                    [
                        "mode" => "REQUIRED",
                        "name" => "object",
                        "type" => "RECORD",
                        "fields" => [
                            [
                                "mode" => "REQUIRED",
                                "name" => "float",
                                "type" => "FLOAT",
                            ],
                            [
                                "mode" => "REQUIRED",
                                "name" => "int",
                                "type" => "INTEGER",
                            ],
                        ]
                    ],
                ]
            ]
        ]);
    }

    /**
     * @dataProvider validValueProvider()
     */
    public function testSchemaMatches($testValue)
    {
        $this->assertTrue(
            $this->constraint->evaluate($testValue, '', true)
        );
    }

    /**
     * @dataProvider invalidValueProvider()
     */
    public function testSchemaFailures($testValue)
    {
        $this->assertFalse(
            $this->constraint->evaluate($testValue, '', true)
        );
    }

    public function invalidValueProvider ()
    {
        return [
            'null for repeated' => [
                [
                    'id' => '',
                    'created' => microtime(true),
                    'outgoing' => null,
                ]
            ],
            'array null for repeated' => [
                [
                    'id' => '',
                    'created' => microtime(true),
                    'outgoing' => [null],
                ]
            ],
            'string for struct' => [
                [
                    'id' => '',
                    'created' => null,
                    'outgoing' => [''],
                ]
            ],
            'null for timestamp' => [
                [
                    'id' => '',
                    'created' => null,
                ]
            ],
            'string for timestamp' => [
                [
                    'id' => '',
                    'created' => uniqid(),
                ]
            ]
        ];
    }

    public function validValueProvider()
    {
        return [
            'full object' => [
                [
                    'id' => '',
                    'created' => microtime(true),
                    'outgoing' => [
                        [
                            'id' => 1.1,
                            'created' => microtime(true),
                            'object' => [
                                'float' => 2.3,
                                'int' => -1,
                            ],
                        ],
                        [
                            'id' => -1.1,
                            'created' => microtime(true),
                            'object' => [
                                'float' => 2.3,
                                'int' => -1,
                            ],
                        ],
                        [
                            'id' => 344324324.0,
                            'created' => microtime(true),
                            'object' => [
                                'float' => 2.3,
                                'int' => -1,
                            ],
                        ],
                    ],
                ],
            ],
            'empty outgoing property' => [
                [
                    'id' => '',
                    'created' => microtime(true),
                    'outgoing' => [],
                ],
            ],
            'no outgoing property' => [
                [
                    'id' => '',
                    'created' => microtime(true),
                ],
            ],
        ];
    }
}