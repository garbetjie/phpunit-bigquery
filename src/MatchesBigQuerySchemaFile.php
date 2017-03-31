<?php

namespace Garbetjie\PHPUnit\BigQuery;

class MatchesBigQuerySchemaFile extends MatchesBigQuerySchemaJson
{
    /**
     * MatchesBigQuerySchemaFile constructor.
     *
     * Used to load JSON from a file.
     *
     * @param string $schemaJsonFilePath
     *
     * @throws \RuntimeException
     */
    public function __construct($schemaJsonFilePath)
    {
        if (!is_file($schemaJsonFilePath) || !is_readable($schemaJsonFilePath)) {
            throw new \RuntimeException("json schema file path '{$schemaJsonFilePath}' does not exist.");
        }

        $jsonSchema = file_get_contents($schemaJsonFilePath);
        $jsonSchema = json_decode($jsonSchema);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($jsonSchema)) {
            throw new \RuntimeException("invalid json encountered in json schema file '{$schemaJsonFilePath}'");
        }

        parent::__construct($jsonSchema);
    }

}