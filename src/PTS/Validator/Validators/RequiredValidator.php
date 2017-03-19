<?php
declare(strict_types=1);

namespace PTS\Validator\Validators;

class RequiredValidator
{
    public function __invoke($value): bool
    {
        if ($value === null) {
           return false;
        }

        if (is_string($value) && trim($value) === '') {
            return false;
        }

        if ((is_array($value) || $value instanceof \Countable) && count($value) < 1) {
            return false;
        }

        return true;
    }
}
