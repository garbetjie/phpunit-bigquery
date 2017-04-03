<?php

namespace Garbetjie\PHPUnit\BigQuery\Tests\Field;

use Garbetjie\PHPUnit\BigQuery\Field\BytesField;
use Garbetjie\PHPUnit\BigQuery\Field\FloatField;
use Garbetjie\PHPUnit\BigQuery\Field\IntegerField;
use Garbetjie\PHPUnit\BigQuery\Field\StringField;
use Garbetjie\PHPUnit\BigQuery\Field\StructField;
use Garbetjie\PHPUnit\BigQuery\Field\TimestampField;
use Garbetjie\PHPUnit\BigQuery\Mode;
use PHPUnit\Framework\TestCase;

class StructFieldTest extends TestCase
{
    public function testWithRequiredProperties ()
    {
        $structField = new StructField('struct', Mode::REQUIRED);
        $structField->setNested([
            new BytesField('bytes', Mode::REQUIRED),
            new FloatField('float', Mode::REQUIRED),
            new IntegerField('integer', Mode::REQUIRED),
            new StringField('string', Mode::REQUIRED),
            new TimestampField('timestamp', Mode::REQUIRED),
        ]);

        $testValueAsArray = [
            'bytes' => base64_encode('Hello, World!'),
            'float' => 1.23456789,
            'integer' => 1,
            'string' => 'Hello, World!',
            'timestamp' => microtime(true),
        ];
        $testValueAsObject = json_decode(json_encode($testValueAsArray));


        $this->assertTrue($structField->isValueAllowed($testValueAsArray), 'testing as array');
        $this->assertTrue($structField->isValueAllowed($testValueAsObject), 'testing as object');
    }

    public function testNullableStruct ()
    {
        $this->assertTrue(
            (new StructField('struct', Mode::NULLABLE))->isValueAllowed(null)
        );

        $this->assertFalse(
            (new StructField('struct', Mode::REQUIRED))->isValueAllowed(null)
        );
    }

    public function testWithMixedProperties()
    {
        $structField = new StructField('struct', Mode::REQUIRED);
        $structField->setNested([
            new BytesField('bytes', Mode::NULLABLE),
            new FloatField('float', Mode::NULLABLE),
            new IntegerField('integer', Mode::REQUIRED),
            new StringField('string', Mode::NULLABLE),
            new TimestampField('timestamp', Mode::REQUIRED),
        ]);

        $testValueAsArray = [
            'bytes' => null,
            'float' => null,
            'integer' => 0,
            'string' => null,
            'timestamp' => microtime(true),
        ];
        $testValueAsObject = json_decode(json_encode($testValueAsArray));


        $this->assertTrue($structField->isValueAllowed($testValueAsArray), 'testing as array');
        $this->assertTrue($structField->isValueAllowed($testValueAsObject), 'testing as object');
    }

    public function testNestedStructProperty()
    {
        $rootStructField = new StructField('root', Mode::REQUIRED);
        $childStructField = new StructField('child', Mode::REQUIRED);

        $childStructField->setNested([
            new BytesField('bytes', Mode::NULLABLE),
            new FloatField('float', Mode::NULLABLE),
            new IntegerField('integer', Mode::REQUIRED),
            new StringField('string', Mode::NULLABLE),
            new TimestampField('timestamp', Mode::REPEATED),
        ]);
        $rootStructField->setNested([$childStructField]);

        $testValueAsArray = [
            'child' => [
                'bytes' => base64_encode('Hello, World!'),
                'float' => 1.23456789,
                'integer' => -1,
                'string' => null,
                'timestamp' => [
                    microtime(true),
                    43.65654,
                ]
            ],
        ];
        $testValueAsObject = json_decode(json_encode($testValueAsArray));

        $this->assertTrue($rootStructField->isValueAllowed($testValueAsArray));
        $this->assertTrue($rootStructField->isValueAllowed($testValueAsObject));

        $testValueAsArray['child']['timestamp'][] = null;
        $testValueAsObject = json_decode(json_encode($testValueAsArray));

        $this->assertFalse($rootStructField->isValueAllowed($testValueAsArray));
        $this->assertFalse($rootStructField->isValueAllowed($testValueAsObject));
    }

    public function testWithNullableProperties()
    {
        $structField = new StructField('struct', Mode::REQUIRED);
        $structField->setNested([
            new BytesField('bytes', Mode::NULLABLE),
            new FloatField('float', Mode::NULLABLE),
            new IntegerField('integer', Mode::NULLABLE),
            new StringField('string', Mode::NULLABLE),
            new TimestampField('timestamp', Mode::NULLABLE),
        ]);

        $testValueAsArray = [
            'bytes' => null,
            'float' => null,
            'integer' => null,
            'string' => null,
            'timestamp' => null,
        ];
        $testValueAsObject = json_decode(json_encode($testValueAsArray));


        $this->assertTrue($structField->isValueAllowed($testValueAsArray), 'testing as array');
        $this->assertTrue($structField->isValueAllowed($testValueAsObject), 'testing as object');
    }

    public function testWithEmptyStructProperty()
    {
        $structField = new StructField('struct', Mode::REQUIRED);

        $this->assertFalse($structField->isValueAllowed(null));
    }

    public function testWithRepeatedProperties()
    {
        $structField = new StructField('struct', Mode::REQUIRED);
        $structField->setNested([new StringField('string', Mode::REPEATED)]);

        $this->assertFalse($structField->isValueAllowed(["string" => null]));
        $this->assertFalse($structField->isValueAllowed(["string" => ""]));
        $this->assertFalse($structField->isValueAllowed(["string" => [null]]));
        $this->assertTrue($structField->isValueAllowed(["string" => [""]]));
    }
}