<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Type;
use Garbetjie\PHPUnit\BigQuery\Field\AbstractField;

class StringField extends AbstractField
{
    /**
     * @var string
     */
    protected $type = Type::STRING;

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        return is_string($value);
    }
}