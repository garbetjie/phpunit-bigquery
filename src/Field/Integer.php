<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Type;
use Garbetjie\PHPUnit\BigQuery\Field\Field;

class Integer extends Field
{
    /**
     * @var string
     */
    protected $type = Type::INTEGER;

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        return is_integer($value);
    }
}