<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;

class BetweenFloatValidatorTest extends TestCase
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
     * @param int $expectedCountErrors
     *
     * @dataProvider providerData
     *
     * @throws \PTS\Validator\ValidatorRuleException
     */
    public function testBetweenFloatValidator(array $data, array $rules, int $expectedCountErrors = 0): void
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
                ['age' => 1.0],
                ['age' => [['betweenFloat' => [0, 100]]]],
                0
            ],
            [
                ['age' => 1.0],
                ['age' => ['betweenFloat:0:100']],
                0
            ],
            [
                ['age' => 1.0],
                ['age' => ['betweenFloat:4:100']],
                1
            ],
            [
                ['age' => -1.0],
                ['age' => ['betweenFloat:0:100']],
                1
            ],
            [
                ['age' => 101.0],
                ['age' => ['betweenFloat:0:100']],
                1
            ],
            [
                ['age' => 22.1],
                ['age' => ['betweenFloat:22.1:100']],
                0
            ],
            [
                ['age' => 22.0],
                ['age' => ['betweenFloat:22.1:100']],
                1
            ],
            [
                ['age' => 22.0],
                ['age' => ['betweenFloat:22.0:23']],
                0
            ],
            [
                ['age' => 23.2],
                ['age' => ['betweenFloat:22.0:23.2']],
                0
            ],
        ];
    }
}
