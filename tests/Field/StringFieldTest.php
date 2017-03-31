<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests\Field;

use Garbetjie\PHPUnit\BigQuery\Field\StringField;
use Garbetjie\PHPUnit\BigQuery\Mode;
use PHPUnit\Framework\TestCase;

class StringFieldTest extends TestCase
{

    /**
     * @dataProvider allowedValueProvider()
     */
    public function testAllowedValue($value)
    {
        $field = new StringField("test", Mode::REQUIRED);

        $this->assertTrue($field->isValueAllowed($value));
    }

    /**
     * @dataProvider invalidValueProvider()
     */
    public function testInvalidValue($value)
    {
        $field = new StringField("test", Mode::REQUIRED);

        $this->assertFalse($field->isValueAllowed($value));
    }

    public function testNullValue()
    {
        $field = new StringField('test', Mode::REQUIRED);
        $this->assertFalse($field->isValueAllowed(null));

        $field = new StringField('test', Mode::NULLABLE);
        $this->assertTrue($field->isValueAllowed(null));
    }

    public function allowedValueProvider()
    {
        return [
            'empty string' => [''],
            'non-empty string' => ['hi!'],
        ];
    }

    public function invalidValueProvider ()
    {
        $invalidValues = [
            'integer' => [1],
            'float' => [1.1],
            'array' => [[]],
            'object' => [new \stdClass()],
            'callable' => [function () {}],
            'boolean false' => [false],
            'boolean true' => [true],
        ];

        $fp = tmpfile();
        if (is_resource($fp)) {
            $invalidValues['resource'] = [$fp];
        }

        return $invalidValues;
    }
}