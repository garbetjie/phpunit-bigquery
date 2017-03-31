<?php

namespace Garbetjie\PHPUnit\BigQuery;

final class Mode
{
    const REQUIRED = 'REQUIRED';
    const NULLABLE = 'NULLABLE';
    const REPEATED = 'REPEATED';

    private function __construct()
    {
        // void
    }
}