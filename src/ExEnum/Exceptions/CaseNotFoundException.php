<?php

namespace ExEnum\Exceptions;

class CaseNotFoundException extends \Exception
{
    public function __construct(
        public string $case_name = '',
    ) {
        $this->message = "Specified case [ {$case_name} ] not found.";
    }
}
