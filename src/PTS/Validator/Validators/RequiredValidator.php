<?php
declare(strict_types=1);

namespace PTS\Validator\Validators;

class RequiredValidator
{
    public function __invoke($value): bool
    {
        if ($value === null || $this->validateString($value)) {
            return false;
        }

        return !$this->validateCountable($value);
    }

    protected function validateString($value): bool
    {
        return is_string($value) && trim($value) === '';
    }

    protected function validateCountable($value): bool
    {
        return (is_array($value) || $value instanceof \Countable) && count($value) < 1;
    }
}
