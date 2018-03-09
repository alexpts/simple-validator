<?php
namespace PTS\Validator\Validators;

class BetweenFloatValidator
{
    public function __invoke($value, float $min, float $max): bool
    {
        return $value <= $max && $value >= $min;
    }
}
