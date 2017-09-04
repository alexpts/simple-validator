<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;

class InArrayValidatorTest extends TestCase
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
     */
    public function testInArrayValidator(array $data, array $rules, int $expectedCountErrors = 0): void
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
                ['name' => [
                    ['inArray' => [['Jonn', 'Dima']]],
                    ['max' => [5]]
                ]],
                1
            ],

            [
                ['name' => 'Alex'],
                ['name' => [
                    ['inArray' => [['Jonn', 'Alex']]],
                    ['max' => [5]]
                ]],
                0
            ],

            [
                ['name' => 1],
                ['name' => [
                    ['inArray' => [[1, 2, 3]]]
                ]],
                0
            ]
        ];
    }
}
