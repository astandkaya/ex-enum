<?php

namespace ExEnum\Exceptions;

class EnumNotFoundException extends \Exception
{
    public function __construct(
        public string $enum_name = '',
    ) {
        $this->message = "Specified enum [ {$enum_name} ] not found.";
    }
}
