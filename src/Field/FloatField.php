<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Type;
use Garbetjie\PHPUnit\BigQuery\Field\AbstractField;

class FloatField extends AbstractField
{
    /**
     * @var string
     */
    protected $type = Type::FLOAT;

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        return is_float($value);
    }
}