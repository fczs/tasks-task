<?php

namespace App\ValueObjects;

use App\ValueObjects\Contracts\ValueObject;

class TaskID extends ValueObject
{
    /**
     * Generates UUID4 with random 16 bytes
     *
     * @return string
     */
    public function generateUIDv4()
    {
        $data = $this->value;
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}