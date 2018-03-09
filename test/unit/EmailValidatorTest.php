<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;

class EmailValidatorTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp(): void
    {
        $this->validator = new Validator(new DeepArray);
    }

    /**
     * @param array $data
     * @param array $rules
     * @param int   $expectedCountErrors
     *
     * @dataProvider providerData
     *
     * @throws \PTS\Validator\ValidatorRuleException
     */
    public function testEmailValidator(array $data, array $rules, int $expectedCountErrors = 0): void
    {
        $errors = $this->validator->validate($data, $rules);
        $errors2 = $this->validator->validateIfExists($data, $rules);

        self::assertCount($expectedCountErrors, $errors);
        self::assertCount($expectedCountErrors, $errors2);
    }

    public function providerData(): array
    {
        return [
            [
                ['email' => 'true'],
                ['email' => ['email']],
                1
            ],
            [
                ['email' => 'alex@php.io'],
                ['email' => ['email']],
                0
            ],
            [
                ['email' => 'alex@php'],
                ['email' => ['email']],
                1
            ],
            [
                ['email' => 'alex.io'],
                ['email' => ['email']],
                1
            ],
            [
                ['email' => '@php.io'],
                ['email' => ['email']],
                1
            ],
        ];
    }
}
