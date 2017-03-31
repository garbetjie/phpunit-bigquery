<?php

namespace Garbetjie\PHPUnit\BigQuery\Field;

use Garbetjie\PHPUnit\BigQuery\Mode;

abstract class Field
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
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValueAllowed($value)
    {
        if ($value === null) {
            return !$this->isRequired();
        }

        if ($this->isRepeatable()) {
            if (!is_array($value)) {
                return false;
            } else {
                return array_filter($value, [$this, 'validateValue']) === $value;
            }
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