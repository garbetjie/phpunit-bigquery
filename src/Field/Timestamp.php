<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Type;
use Garbetjie\PHPUnit\BigQuery\Field\Field;

class Timestamp extends Field
{
    protected $type = Type::TIMESTAMP;

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        return is_float($value);
    }
}