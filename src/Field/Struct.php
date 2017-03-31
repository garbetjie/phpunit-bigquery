<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Field\Field;
use Garbetjie\PHPUnit\BigQuery\Field\Nestable;
use Garbetjie\PHPUnit\BigQuery\FieldFactory;
use Garbetjie\PHPUnit\BigQuery\Type;
use Garbetjie\PHPUnit\BigQuery\InvalidFieldException;

class Struct extends Field implements Nestable
{
    /**
     * @var Field[]
     */
    private $nested = [];

    /**
     * @var string
     */
    protected $type = Type::STRUCT;

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        if (is_array($value) && count($value) > 0) {
            // void
        } elseif (is_object($value) && count(get_object_vars($value)) > 0) {
            // void
        } else {
            return false;
        }

        // Now, we need to match against the field types too.
        foreach ($this->nested as $schemaField) {
            if (!$schemaField->isValueAllowed($value[$schemaField->getName()])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function setNested(array $fields)
    {
        if (count($fields) < 1) {
            throw new InvalidFieldException("at least one property required for nested schema.");
        }

        $factory = new FieldFactory();

        foreach ($fields as $field) {
            $this->nested[] = $factory->buildField($field);
        }
    }
}