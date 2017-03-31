<?php

namespace Garbetjie\PHPUnit\BigQuery;

final class Type
{
    const STRING = 'STRING';
    const TIMESTAMP = 'TIMESTAMP';
    const FLOAT = 'FLOAT';
    const RECORD = 'RECORD';
    const STRUCT = 'RECORD';
    const BYTES = 'BYTES';
    const INTEGER = 'INTEGER';

    private function __construct()
    {
        // void
    }
}