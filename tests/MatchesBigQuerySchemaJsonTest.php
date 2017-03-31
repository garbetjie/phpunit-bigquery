<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests;

use Garbetjie\PHPUnit\BigQuery\MatchesBigQuerySchemaJson;
use PHPUnit\Framework\TestCase;

class MatchesBigQuerySchemaJsonTest extends TestCase
{
    public function testSchemaMatches()
    {
        $constraint = new MatchesBigQuerySchemaJson([
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
                            ],[
                                "mode" => "REQUIRED",
                                "name" => "int",
                                "type" => "INTEGER",
                            ],
                        ]
                    ],
                ]
            ]
        ]);

        $testValue = [
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
        ];

        $this->assertTrue(
            $constraint->evaluate($testValue, '', true)
        );
    }
}