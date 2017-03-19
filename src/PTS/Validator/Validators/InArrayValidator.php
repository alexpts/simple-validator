<?php
namespace PTS\Validator\Validators;

class InArrayValidator
{
    public function __invoke($value, array $allow, bool $strict = true): bool
    {
        return in_array($value, $allow, $strict);
    }
}
