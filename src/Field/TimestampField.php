<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Type;
use Garbetjie\PHPUnit\BigQuery\Field\AbstractField;

class TimestampField extends AbstractField
{
    protected $type = Type::TIMESTAMP;

    /**
     * @inheritdoc
     *
     * @TODO implement various formats available from https://cloud.google.com/bigquery/data-types
     */
    protected function validateValue($value)
    {
        return is_float($value);
    }
}