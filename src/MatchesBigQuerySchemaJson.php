<?php

namespace Garbetjie\PHPUnit\BigQuery;

use Garbetjie\PHPUnit\BigQuery\Field\AbstractField;
use Garbetjie\PHPUnit\BigQuery\Field\StructField;

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
     *
     * @codeCoverageIgnore
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

        // Create a fake STRUCT field - the validation is the same.
        $fakeStructRoot = new StructField('fake_root', Mode::REQUIRED);
        $fakeStructRoot->setNested($this->schemaFields);

        return $fakeStructRoot->isValueAllowed($jsonObject);
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