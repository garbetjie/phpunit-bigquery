<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Type;
use Garbetjie\PHPUnit\BigQuery\Field\Field;

class String extends Field
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