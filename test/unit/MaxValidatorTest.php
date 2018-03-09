<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;

class MaxValidatorTest extends TestCase
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
    public function testMaxValidator(array $data, array $rules, int $expectedCountErrors = 0): void
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
                ['name' => 'Alex'],
                ['name' => ['max:4', 'min:3']],
                0
            ],
            [
                ['name' => 'Alex'],
                ['name' => ['max:3']],
                1
            ],
            [
                ['name' => 'Alex'],
                ['name' => ['max:5']],
                0
            ],

            [
                ['name' => ['Alex']],
                ['name' => ['max:1']],
                0
            ],
            [
                ['name' => ['Alex']],
                ['name' => ['max:0']],
                1
            ],
            [
                ['name' => ['Alex']],
                ['name' => ['max:2']],
                0
            ],

            [
                ['age' => 20],
                ['age' => ['max:18']],
                1
            ],
            [
                ['age' => 18],
                ['age' => ['max:18']],
                0
            ],
            [
                ['age' => 12],
                ['age' => ['max:18']],
                0
            ],

            [
                // compare as string, not as number
                ['age' => '2000'],
                ['age' => ['max:3']],
                1
            ],
            [
                ['age' => '2000'],
                ['age' => ['max:4']],
                0
            ],
            [
                ['age' => '2000'],
                ['age' => ['max:5']],
                0
            ]
        ];
    }
}
