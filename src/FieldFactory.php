<?php

namespace Garbetjie\PHPUnit\BigQuery;

use Garbetjie\PHPUnit\BigQuery\Field\Field;
use Garbetjie\PHPUnit\BigQuery\Field\Bytes;
use Garbetjie\PHPUnit\BigQuery\Field\Float;
use Garbetjie\PHPUnit\BigQuery\Field\Integer;
use Garbetjie\PHPUnit\BigQuery\Field\Nestable;
use Garbetjie\PHPUnit\BigQuery\Field\String;
use Garbetjie\PHPUnit\BigQuery\Field\Struct;
use Garbetjie\PHPUnit\BigQuery\Field\Timestamp;

class FieldFactory
{
    private $fieldTypeToClassMapping = [
        Type::BYTES => Bytes::class,
        Type::FLOAT => Float::class,
        Type::INTEGER => Integer::class,
        Type::RECORD => Struct::class,
        Type::STRING => String::class,
        Type::STRUCT => Struct::class,
        Type::TIMESTAMP => Timestamp::class,
    ];

    /**
     * @param array|\stdClass|Field $object
     *
     * @return Field
     */
    public function buildField ($object)
    {
        if ($object instanceof Field) {
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
        /* @var Field $builtField */

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