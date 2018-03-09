<?php
declare(strict_types=1);

namespace PTS\Validator\Validators;

class EmailValidator
{
    public function __invoke($value): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
