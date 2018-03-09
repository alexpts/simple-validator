<?php
namespace PTS\Validator;

use PTS\Tools\DeepArray;
use PTS\Validator\Validators\AlphaDashValidator;
use PTS\Validator\Validators\AlphaNumValidator;
use PTS\Validator\Validators\AlphaValidator;
use PTS\Validator\Validators\BetweenFloatValidator;
use PTS\Validator\Validators\BetweenIntValidator;
use PTS\Validator\Validators\DateTimeValidator;
use PTS\Validator\Validators\DateValidator;
use PTS\Validator\Validators\EmailValidator;
use PTS\Validator\Validators\InArrayValidator;
use PTS\Validator\Validators\MaxValidator;
use PTS\Validator\Validators\MinValidator;
use PTS\Validator\Validators\RequiredValidator;

class Validator
{
    /** @var string */
    protected $paramDelimiter = ':';
    /** @var string */
    protected $keysDelimiter = '.';

    /** @var DeepArray */
    protected $deepArrayService;

    /** @var ValidatorRuleException */
    protected $notExistValue;

    /** @var callable[] */
    protected $rulesHandlers = [];

    public function __construct(DeepArray $deepArrayService)
    {
        $this->notExistValue = new ValidatorRuleException('Value is not exists');
        $this->deepArrayService = $deepArrayService;

        $this->registerRule('string', 'is_string')
            ->registerRule('int', 'is_int')
            ->registerRule('float', 'is_float')
            ->registerRule('array', 'is_array')
            ->registerRule('required', new RequiredValidator)
            ->registerRule('betweenInt', new BetweenIntValidator)
            ->registerRule('betweenFloat', new BetweenFloatValidator)
            ->registerRule('bool', 'is_bool')
            ->registerRule('strictBool', 'is_bool')
            ->registerRule('alpha', new AlphaValidator)
            ->registerRule('alphaDash', new AlphaDashValidator)
            ->registerRule('alphaNum', new AlphaNumValidator)
            ->registerRule('date', new DateValidator)
            ->registerRule('dateTime', new DateTimeValidator)
            ->registerRule('inArray', new InArrayValidator)
            ->registerRule('min', new MinValidator)
            ->registerRule('max', new MaxValidator)
            ->registerRule('email', new EmailValidator);
    }

    /**
     * @param string $name
     * @param callable $handler
     * @return $this
     */
    public function registerRule(string $name, callable $handler): self
    {
        $this->rulesHandlers[$name] = $handler;
        return $this;
    }

    public function getRules(): array
    {
        return $this->rulesHandlers;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param bool  $validateIfExist
     *
     * @return array
     * @throws ValidatorRuleException
     */
    public function validate(array $data, array $rules, bool $validateIfExist = false): array
    {
        $errors = [];

        foreach ($rules as $name => $attrRules) {
            $value = $this->getValue($name, $data, $this->notExistValue);

            if (!($value instanceof $this->notExistValue)) {
                $errors[$name] = $this->validateValue($value, $attrRules);
                continue;
            }

            if (!$validateIfExist) {
                $errors[$name] = 'Value is not exists or bad: '.$name;
            }
        }

        return array_filter($errors);
    }

    /**
     * @param array $data
     * @param array $rules
     *
     * @return array
     *
     * @throws ValidatorRuleException
     */
    public function validateIfExists(array $data, array $rules): array
    {
        return $this->validate($data, $rules, true);
    }

    /**
     * @param string $name
     * @param array $data
     * @param mixed $default
     * @return mixed
     */
    protected function getValue(string $name, array $data, $default)
    {
        $names = explode($this->keysDelimiter, $name);
        return $this->deepArrayService->getAttr($names, $data, $default);
    }

    /**
     * @param mixed $value
     * @param array $rules
     *
     * @return array - errors
     *
     * @throws ValidatorRuleException
     */
    protected function validateValue($value, array $rules): array
    {
        $errors = [];

        foreach ($rules as $rule) {
            list($handlerAlias, $params) = \is_string($rule)
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
