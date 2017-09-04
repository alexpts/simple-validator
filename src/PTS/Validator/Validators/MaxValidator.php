<?php
declare(strict_types = 1);

namespace PTS\Validator\Validators;

class MaxValidator
{
    public function __invoke($value, int $max): bool
    {
        if (is_int($value) || is_float($value)) {
            return $value <= $max;
        }

        if (is_array($value)) {
            return count($value) <= $max;
        }

        return mb_strlen($value) <= $max;
    }
}
