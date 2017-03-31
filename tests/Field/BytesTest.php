<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests\Field;

use Garbetjie\PHPUnit\BigQuery\Field\Bytes;
use Garbetjie\PHPUnit\BigQuery\Mode;
use PHPUnit\Framework\TestCase;

class BytesTest extends TestCase
{

    /**
     * @dataProvider allowedValueProvider()
     */
    public function testAllowedValue($value)
    {
        $field = new Bytes('test', Mode::REQUIRED);

        $this->assertTrue($field->isValueAllowed($value));
    }

    /**
     * @dataProvider invalidValueProvider()
     */
    public function testInvalidValue($value)
    {
        $field = new Bytes('test', Mode::REQUIRED);

        $this->assertFalse($field->isValueAllowed($value));
    }

    public function testNullValue()
    {
        $field = new Bytes('test', Mode::REQUIRED);
        $this->assertFalse($field->isValueAllowed(null));

        $field = new Bytes('test', Mode::NULLABLE);
        $this->assertTrue($field->isValueAllowed(null));
    }

    public function allowedValueProvider()
    {
        return [
            'empty string' => [''],
            'non-empty string' => [base64_encode('hello')],
        ];
    }

    public function invalidValueProvider()
    {
        return [
            'integer' => [1],
            'invalid string' => ['FDS!@#$'],
            'array' => [[]],
            'object' => [new \stdClass()],
            'float' => [1.1],
            'negative integer' => [-203]
        ];
    }
}