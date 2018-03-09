<?php
declare(strict_types=1);

namespace PTS\Validator\Validators;

class DateTimeValidator
{
    public function __invoke($value): bool
    {
        return $value instanceof \DateTime;
    }
}
