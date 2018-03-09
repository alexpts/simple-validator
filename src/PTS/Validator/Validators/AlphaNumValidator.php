<?php
declare(strict_types=1);

namespace PTS\Validator\Validators;

class AlphaNumValidator
{
    public function __invoke($value): bool
    {
        if (!\is_string($value) && !\is_numeric($value)) {
            return false;
        }

        return preg_match('/^[\pL\pM\pN]+$/u', $value) > 0;
    }
}
