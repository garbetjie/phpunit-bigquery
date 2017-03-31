<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests\Field;

use Garbetjie\PHPUnit\BigQuery\Field\Float;
use Garbetjie\PHPUnit\BigQuery\Mode;
use PHPUnit\Framework\TestCase;

class FloatTest extends TestCase
{

    /**
     * @dataProvider allowedValueProvider()
     */
    public function testAllowedValue($value)
    {
        $field = new Float('test', Mode::REQUIRED);

        $this->assertTrue($field->isValueAllowed($value));
    }

    /**
     * @dataProvider invalidValueProvider()
     */
    public function testInvalidValue($value)
    {
        $field = new Float('test', Mode::REQUIRED);

        $this->assertFalse($field->isValueAllowed($value));
    }

    public function testNullValue()
    {
        $field = new Float('test', Mode::REQUIRED);
        $this->assertFalse($field->isValueAllowed(null));

        $field = new Float('test', Mode::NULLABLE);
        $this->assertTrue($field->isValueAllowed(null));
    }

    public function allowedValueProvider()
    {
        return [
            'float' => [1.1],
            'whole number float' => [1.0],
            'negative whole number float' => [-1.0],
            'negative float' => [-1.1],
            'zero' => [0.0],
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