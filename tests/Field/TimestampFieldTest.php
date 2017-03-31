<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests\Field;

use Garbetjie\PHPUnit\BigQuery\Field\TimestampField;
use Garbetjie\PHPUnit\BigQuery\Mode;
use PHPUnit\Framework\TestCase;

class TimestampFieldTest extends TestCase
{

    /**
     * @dataProvider allowedValueProvider()
     */
    public function testAllowedValue($value)
    {
        $field = new TimestampField('test', Mode::REQUIRED);

        $this->assertTrue($field->isValueAllowed($value));
    }

    /**
     * @dataProvider invalidValueProvider()
     */
    public function testInvalidValue($value)
    {
        $field = new TimestampField('test', Mode::REQUIRED);

        $this->assertFalse($field->isValueAllowed($value));
    }

    public function testNullValue()
    {
        $field = new TimestampField('test', Mode::REQUIRED);
        $this->assertFalse($field->isValueAllowed(null));

        $field = new TimestampField('test', Mode::NULLABLE);
        $this->assertTrue($field->isValueAllowed(null));
    }

    /**
     * @dataProvider allowedValueProvider()
     */
    public function testRepeatedAllowedValues ($value)
    {
        $field = new TimestampField('test', Mode::REPEATED);

        $this->assertFalse($field->isValueAllowed($value));
        $this->assertTrue($field->isValueAllowed([$value]));
        $this->assertTrue($field->isValueAllowed([]));
    }

    public function allowedValueProvider()
    {
        return [
            'float' => [1.1],
            'huge float' => [PHP_INT_MAX - 1.23451],
            'whole number float' => [1.0],
            'zero float' => [0.0],
            'negative whole number float' => [-1.0],
            'negative float' => [-1.1],
        ];
    }

    public function invalidValueProvider ()
    {
        return [
            'empty string' => [''],
            'non-empty string' => ['hello'],
            'integer' => [1],
            'zero integer' => [0],
            'array' => [[]],
            'object' => [new \stdClass()],
            'negative integer' => [-203],
        ];
    }
}