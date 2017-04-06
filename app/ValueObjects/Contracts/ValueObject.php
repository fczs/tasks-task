<?php

namespace App\ValueObjects\Contracts;

abstract class ValueObject
{
    protected $value;

    /**
     * @param string
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the raw $value
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}