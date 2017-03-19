<?php
declare(strict_types=1);

namespace PTS\Validator\Validators;

class RequiredValidator
{
    public function __invoke($value): bool
    {
        $result = true;

        if ($value === null) {
            $result = false;
        } elseif (is_string($value) && trim($value) === '') {
            $result = false;
        } elseif ((is_array($value) || $value instanceof \Countable) && count($value) < 1) {
            $result = false;
        } elseif ($value instanceof \SplFileInfo) {
            $result =  (string) $value->getPath() !== '';
        }

        return $result;
    }
}