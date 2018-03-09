<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;

class AlphaNumValidatorTest extends TestCase
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
    public function testAlphaNum(array $data, array $rules, int $expectedCountErrors = 0): void
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
                ['name' => 'alex'],
                ['name' => ['alphaNum']],
                0
            ],
            [
                ['name' => '1234'],
                ['name' => ['alphaNum']],
                0
            ],
            [
                ['name' => 'alex123'],
                ['name' => ['alphaNum']],
                0
            ],
            [
                ['name' => 'alex-123'],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => 'alex_123'],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => 'alex-123_213'],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => 'alex#'],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => 'alex$'],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => 'alex()'],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => 'alex[]'],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => 'alex[2]'],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => true],
                ['name' => ['alphaNum']],
                1
            ],
            [
                ['name' => ['alex']],
                ['name' => ['alphaNum']],
                1
            ],
        ];
    }
}
