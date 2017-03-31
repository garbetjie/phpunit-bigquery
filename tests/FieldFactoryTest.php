<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests;

use Garbetjie\PHPUnit\BigQuery\Field\BytesField;
use Garbetjie\PHPUnit\BigQuery\Field\FloatField;
use Garbetjie\PHPUnit\BigQuery\Field\IntegerField;
use Garbetjie\PHPUnit\BigQuery\Field\StructField;
use Garbetjie\PHPUnit\BigQuery\Field\TimestampField;
use Garbetjie\PHPUnit\BigQuery\FieldFactory;
use Garbetjie\PHPUnit\BigQuery\InvalidFieldException;
use Garbetjie\PHPUnit\BigQuery\Mode;
use Garbetjie\PHPUnit\BigQuery\Type;
use PHPUnit\Framework\TestCase;

class FieldFactoryTest extends TestCase
{
    /**
     * @var FieldFactory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();

        $this->factory = new FieldFactory();
    }

    public function testBytesField()
    {
        $this->assertInstanceOf(
            BytesField::class,
            $this->factory->buildField(
                [
                    'name' => 'bytes',
                    'type' => Type::BYTES,
                ]
            )
        );
    }

    public function testTimestampField()
    {
        $this->assertInstanceOf(
            TimestampField::class,
            $this->factory->buildField(
                [
                    'name' => 'timestamp',
                    'type' => Type::TIMESTAMP,
                ]
            )
        );
    }

    public function testFloatField()
    {
        $this->assertInstanceOf(
            FloatField::class,
            $this->factory->buildField(
                [
                    'name' => 'timestamp',
                    'type' => Type::FLOAT,
                ]
            )
        );
    }

    public function testRecordField()
    {
        $this->assertInstanceOf(
            StructField::class,
            $this->factory->buildField(
                [
                    'name' => 'record',
                    'type' => Type::RECORD,
                    'fields' => [
                        ['name' => 'string', 'type' => Type::STRING],
                    ],
                ]
            )
        );
    }

    public function testEmptyRecordField()
    {
        $this->expectException(InvalidFieldException::class);

        $this->assertInstanceOf(
            StructField::class,
            $this->factory->buildField(
                [
                    'name' => 'record',
                    'type' => Type::RECORD,
                ]
            )
        );
    }

    public function testStructField()
    {
        $this->assertInstanceOf(
            StructField::class,
            $this->factory->buildField(
                [
                    'name' => 'record',
                    'type' => Type::STRUCT,
                    'fields' => [
                        ['name' => 'string', 'type' => Type::STRING],
                    ],
                ]
            )
        );
    }

    public function testEmptyStructField()
    {
        $this->expectException(InvalidFieldException::class);

        $this->assertInstanceOf(
            StructField::class,
            $this->factory->buildField(
                [
                    'name' => 'record',
                    'type' => Type::STRUCT,
                ]
            )
        );
    }

    public function testIntegerField()
    {
        $this->assertInstanceOf(
            IntegerField::class,
            $this->factory->buildField(
                [
                    'name' => 'record',
                    'type' => Type::INTEGER,
                ]
            )
        );
    }
}