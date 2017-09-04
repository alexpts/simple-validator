<?php
declare(strict_types = 1);

namespace PTS\Validator\Validators;

class AlphaDashValidator
{
    public function __invoke($value): bool
    {
        return (!is_string($value) && !is_numeric($value)) && preg_match('/^[\pL\pM\pN_-]+$/u', $value) > 0;
    }
}
