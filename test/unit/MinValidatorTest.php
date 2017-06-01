<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Validator\Validator;

class MinValidatorTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param int $expectedCountErrors
     *
     * @dataProvider providerData
     */
    public function testMinValidator(array $data, array $rules, int $expectedCountErrors = 0): void
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
                ['name' => ['min:4']],
                0
            ],
            [
                ['name' => 'Alex'],
                ['name' => ['min:3']],
                0
            ],
            [
                ['name' => 'Alex'],
                ['name' => ['min:5']],
                1
            ],

            [
                ['name' => ['Alex']],
                ['name' => ['min:1']],
                0
            ],
            [
                ['name' => ['Alex']],
                ['name' => ['min:0']],
                0
            ],
            [
                ['name' => ['Alex']],
                ['name' => ['min:2']],
                1
            ],

            [
                ['age' => 20],
                ['age' => ['min:18']],
                0
            ],
            [
                ['age' => 18],
                ['age' => ['min:18']],
                0
            ],
            [
                ['age' => 12],
                ['age' => ['min:18']],
                1
            ],

            [
                // compare as string, not as number
                ['age' => '20'],
                ['age' => ['min:4']],
                1
            ],
            [
                ['age' => '20'],
                ['age' => ['min:2']],
                0
            ],
            [
                ['age' => '2'],
                ['age' => ['min:1']],
                0
            ]
        ];
    }
}
