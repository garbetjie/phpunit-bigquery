<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests\Field;

use Garbetjie\PHPUnit\BigQuery\Field\Integer;
use Garbetjie\PHPUnit\BigQuery\Mode;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{

    /**
     * @dataProvider allowedValueProvider()
     */
    public function testAllowedValue($value)
    {
        $field = new Integer('test', Mode::REQUIRED);

        $this->assertTrue($field->isValueAllowed($value));
    }

    /**
     * @dataProvider invalidValueProvider()
     */
    public function testInvalidValue($value)
    {
        $field = new Integer('test', Mode::REQUIRED);

        $this->assertFalse($field->isValueAllowed($value));
    }

    public function testNullValue()
    {
        $field = new Integer('test', Mode::REQUIRED);
        $this->assertFalse($field->isValueAllowed(null));

        $field = new Integer('test', Mode::NULLABLE);
        $this->assertTrue($field->isValueAllowed(null));
    }

    public function allowedValueProvider()
    {
        return [
            'integer' => [1],
            'negative integer' => [-203],
            'zero' => [0],
        ];
    }

    public function invalidValueProvider ()
    {
        return [
            'float' => [1.1],
            'negative float' => [-1.0],
            'zero float' => [0.0],
            'empty string' => [''],
            'non-empty string' => ['hello'],
            'array' => [[]],
            'object' => [new \stdClass()],
        ];
    }
}