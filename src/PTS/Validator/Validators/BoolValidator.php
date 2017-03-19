<?php
namespace PTS\Validator\Validators;

class BoolValidator
{
    public function __invoke($value): bool
    {
        return filter_var($value, \FILTER_VALIDATE_BOOLEAN);
    }
}
