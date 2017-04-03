<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Mode;

abstract class AbstractField
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var string
     */
    protected $type;

    /**
     * AbstractField constructor.
     *
     * @param string $name
     * @param string $mode
     */
    public function __construct($name, $mode)
    {
        $this->name = $name;
        $this->mode = $mode;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->mode === Mode::REQUIRED;
    }

    /**
     * @return bool
     */
    public function isRepeatable()
    {
        return $this->mode == Mode::REPEATED;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValueAllowed($value)
    {
        if ($this->isRepeatable()) {
            // Repeated records *must* be an array.
            if (!is_array($value)) {
                return false;
            }

            // Repeated records cannot be populated with NULL values.
            if (array_search(null, $value, true) !== false) {
                return false;
            }

            return array_filter($value, [$this, 'validateValue']) === $value;
        } elseif ($value === null) {
            return !$this->isRequired() && !$this->isRepeatable();
        } else {
            return $this->validateValue($value);
        }
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected abstract function validateValue($value);

    public function getType()
    {
        return $this->type;
    }
}