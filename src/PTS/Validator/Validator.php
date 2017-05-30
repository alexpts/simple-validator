<?php

namespace PTS\Validator;

use PTS\Validator\Validators\AlphaDashValidator;
use PTS\Validator\Validators\AlphaNumValidator;
use PTS\Validator\Validators\AlphaValidator;
use PTS\Validator\Validators\BetweenIntValidator;
use PTS\Validator\Validators\BoolValidator;
use PTS\Validator\Validators\DateTimeValidator;
use PTS\Validator\Validators\DateValidator;
use PTS\Validator\Validators\InArrayValidator;
use PTS\Validator\Validators\MaxValidator;
use PTS\Validator\Validators\MinValidator;
use PTS\Validator\Validators\RequiredValidator;

class Validator
{
    /** @var bool */
    protected $paramDelimiter = ':';

    /** @var callable[] */
    protected $rulesHandlers = [];

    public function __construct()
    {
        $this->registerRule('string', 'is_string');
        $this->registerRule('int', 'is_int');
        $this->registerRule('array', 'is_array');
        $this->registerRule('required', new RequiredValidator);
        $this->registerRule('betweenInt', new BetweenIntValidator);
        $this->registerRule('strictBool', 'is_bool');
        $this->registerRule('bool', new BoolValidator);
        $this->registerRule('alpha', new AlphaValidator);
        $this->registerRule('alphaDash', new AlphaDashValidator);
        $this->registerRule('alphaNum', new AlphaNumValidator);
        $this->registerRule('date', new DateValidator);
        $this->registerRule('dateTime', new DateTimeValidator);
        $this->registerRule('inArray', new InArrayValidator);
        $this->registerRule('min', new MinValidator);
        $this->registerRule('max', new MaxValidator);
    }

    public function registerRule(string $name, callable $handler)
    {
        $this->rulesHandlers[$name] = $handler;
    }

    public function getRules(): array
    {
        return $this->rulesHandlers;
    }

    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $name => $attrRules) {
            if (!array_key_exists($name, $data)) {
                $errors[$name] = 'Value is not exists: ' . $name;
                continue;
            }

            $value = $data[$name] ?? null;

            $errors[$name] = $this->validateValue($value, $attrRules);
        }

        return array_filter($errors);
    }

    public function validateIfExists(array $data, array $rules): array
    {
        $errors = [];

        foreach ($data as $name => $value) {
            $attrRules = $rules[$name] ?? [];
            $errors[$name] = $this->validateValue($value, $attrRules);
        }

        return array_filter($errors);
    }

    protected function validateValue($value, array $rules): array
    {
        $errors = [];

        foreach ($rules as $rule) {
            [$handlerAlias, $params] = is_string($rule)
                ? $this->extractStringRule($rule)
                : $this->extractArrayRule($rule);

            $handler = $this->rulesHandlers[$handlerAlias] ?? null;

            if (!$handler) {
                throw new ValidatorRuleException("Handler not found for alias: {$handlerAlias}");
            }

            if (!$handler($value, ...$params)) {
                $errors[] = $handlerAlias;
            }
        }

        return $errors;
    }

    public function extractArrayRule(array $rule): array
    {
        return [key($rule), current($rule)];
    }

    public function extractStringRule(string $rule): array
    {
        $params = explode($this->paramDelimiter, $rule);
        $handlerAlias = array_shift($params);

        return [$handlerAlias, (array)$params];
    }
}
