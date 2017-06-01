<?php
declare(strict_types=1);

namespace PTS\Validator\Validators;

class MinValidator
{
    public function __invoke($value, int $min): bool
    {
        if (is_int($value) || is_float($value)) {
            return $value >= $min;
        }

        if (is_array($value)) {
            return count($value) >= $min;
        }

        return mb_strlen($value) >= $min;
    }
}
