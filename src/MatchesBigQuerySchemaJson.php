<?php

namespace Garbetjie\PHPUnit\BigQuery;

use Garbetjie\PHPUnit\BigQuery\Field\AbstractField;

class MatchesBigQuerySchemaJson extends \PHPUnit_Framework_Constraint
{
    /**
     * @var AbstractField[]
     */
    private $schemaFields = [];

    /**
     * MatchesBigQuerySchemaJson constructor.
     *
     * @param array $bigQuerySchema
     */
    public function __construct(array $bigQuerySchema)
    {
        parent::__construct();

        $this->schemaFields = $this->parseSchemaFields($bigQuerySchema);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'matches a valid BigQuery schema';
    }

    /**
     * @param mixed $jsonObject
     *
     * @return bool
     */
    protected function matches($jsonObject)
    {
        if (is_array($jsonObject) && count($jsonObject) > 0) {
            // void
        } elseif (is_object($jsonObject) && count(get_object_vars($jsonObject)) > 0) {
            // void
        } else {
            return false;
        }

        $jsonObject = new \ArrayObject($jsonObject);

        foreach ($this->schemaFields as $schemaField) {
            $givenValue = array_key_exists($schemaField->getName(), $jsonObject) ? $jsonObject[$schemaField->getName()] : null;

            if (!$schemaField->isValueAllowed($givenValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $fields
     *
     * @return AbstractField[]
     */
    private function parseSchemaFields (array $fields)
    {
        $factory = new FieldFactory();
        $parsed = [];

        foreach ($fields as $rawField) {
            $parsed[] = $factory->buildField($rawField);
        }

        return $parsed;
    }
}