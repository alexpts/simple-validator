<?php
namespace PTS\Validator\Validators;

class DateValidator
{
    public function __invoke($value): bool
    {

        if ((!is_string($value) && !is_numeric($value)) || strtotime($value) === false) {
            return false;
        }
        
        $date = date_parse($value);
        return checkdate($date['month'], $date['day'], $date['year']);
    }
}
