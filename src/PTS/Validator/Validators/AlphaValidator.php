<?php
declare(strict_types = 1);

namespace PTS\Validator\Validators;

class AlphaValidator
{
    public function __invoke($value): bool
    {
        return is_string($value) && preg_match('/^[\pL\pM]+$/u', $value);
    }
}
