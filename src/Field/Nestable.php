<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

interface Nestable
{
    /**
     * @param array $fields
     *
     * @return void
     */
    public function setNested (array $fields);
}