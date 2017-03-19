<?php
namespace PTS\Validator\Validators;

class DateTimeValidator
{
    public function __invoke($value): bool
    {
        return $value instanceof \DateTime;
    }
}
