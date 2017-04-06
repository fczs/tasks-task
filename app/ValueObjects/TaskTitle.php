<?php

namespace App\ValueObjects;

use App\ValueObjects\Contracts\ValueObject;

class TaskTitle extends ValueObject
{
    /**
     * Converts special characters and trims the string
     *
     * @return string
     */
    public function convertString()
    {
        return htmlspecialchars(trim($this->value()));
    }
}