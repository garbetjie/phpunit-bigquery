<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Field\Field;
use Garbetjie\PHPUnit\BigQuery\Type;

class Bytes extends Field
{
    /**
     * @var string
     */
    protected $type = Type::BYTES;

    /**
     * @inheritdoc
     */
    protected function validateValue ($value)
    {
        return is_string($value) && preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $value);
    }
}