<?php
declare(strict_types=1);

namespace PTS\Validator\Validators;

class InArrayValidator
{
    public function __invoke($value, array $allow): bool
    {
        return in_array($value, $allow, true);
    }
}
