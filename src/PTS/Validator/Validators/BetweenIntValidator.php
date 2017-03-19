<?php
namespace PTS\Validator\Validators;

class BetweenIntValidator
{
    public function __invoke($value, int $min, int $max): bool
    {
       return  $value <= $max && $value >= $min;
    }
}