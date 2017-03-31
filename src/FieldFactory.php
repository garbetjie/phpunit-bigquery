<?php

namespace Garbetjie\PHPUnit\BigQuery;

use Garbetjie\PHPUnit\BigQuery\Field\AbstractField;
use Garbetjie\PHPUnit\BigQuery\Field\BytesField;
use Garbetjie\PHPUnit\BigQuery\Field\FloatField;
use Garbetjie\PHPUnit\BigQuery\Field\IntegerField;
use Garbetjie\PHPUnit\BigQuery\Field\Nestable;
use Garbetjie\PHPUnit\BigQuery\Field\StringField;
use Garbetjie\PHPUnit\BigQuery\Field\StructField;
use Garbetjie\PHPUnit\BigQuery\Field\TimestampField;

class FieldFactory
{
    private $fieldTypeToClassMapping = [
        Type::BYTES => BytesField::class,
        Type::FLOAT => FloatField::class,
        Type::INTEGER => IntegerField::class,
        Type::RECORD => StructField::class,
        Type::STRING => StringField::class,
        Type::STRUCT => StructField::class,
        Type::TIMESTAMP => TimestampField::class,
    ];

    /**
     * @param array|\stdClass|BytesField $object
     *
     * @return AbstractField
     * @throws InvalidFieldException
     */
    public function buildField ($object)
    {
        if ($object instanceof AbstractField) {
            return clone $object;
        }

        $field = new \ArrayObject($object);

        // Validate name.
        if (!isset($field['name']) || strlen($field['name']) < 1) {
            throw new InvalidFieldException("all fields require a name");
        }

        // Validate type.
        if (!isset($field['type'])) {
            throw new InvalidFieldException("field '{$field['name']}' requires a type");
        } elseif (!$this->isValidFieldType($field['type'])) {
            throw new InvalidFieldException("invalid type '{$field['type']}' for field '{$field['name']}'");
        } else {
            $fieldType = $field['type'];
        }

        // Validate mode.
        $fieldMode = Mode::NULLABLE;

        if (isset($field['mode'])) {
            if (!$this->isValidFieldMode($field['mode'])) {
                throw new InvalidFieldException("invalid mode '{$field['mode']}' for field '{$field['name']}'");
            } else {
                $fieldMode = $field['mode'];
            }
        }

        // Create the field.
        $builtField = new $this->fieldTypeToClassMapping[$fieldType]($field['name'], $fieldMode);
        /* @var AbstractField $builtField */

        if ($builtField instanceof Nestable) {
            if (!isset($field['fields']) || !is_array($field['fields']) || count($field['fields']) < 1) {
                throw new InvalidFieldException("nested fields required for field '{$field['name']}' of type '{$builtField->getType()}'");
            }

            $builtField->setNested($field['fields']);
        }

        return $builtField;
    }

    /**
     * Returns a boolean value indicating whether or not the given field mode is valid.
     *
     * @param string $fieldMode
     * @return bool
     */
    private function isValidFieldMode ($fieldMode)
    {
        switch ($fieldMode) {
            case Mode::NULLABLE:
            case Mode::REPEATED:
            case Mode::REQUIRED:
                return true;

            default:
                return false;
        }
    }

    /**
     * Returns a boolean value indicating whether or not the given field type is valid.
     *
     * @param string $fieldType
     * @return bool
     */
    private function isValidFieldType($fieldType)
    {
        return in_array($fieldType, array_keys($this->fieldTypeToClassMapping), true);
    }
}