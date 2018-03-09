<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;
use PTS\Validator\ValidatorRuleException;

class FloatValidatorTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator(new DeepArray);
    }

    /**
     * @param array $data
     * @param array $rules
     * @param int $expectedCountErrors
     *
     * @dataProvider dataProvider
     *
     * @throws ValidatorRuleException
     */
    public function testValidateFloat(array $data, array $rules, int $expectedCountErrors): void
    {
        $errors = $this->validator->validate($data, $rules);
        $errors2 = $this->validator->validateIfExists($data, $rules);

        self::assertCount($expectedCountErrors, $errors);
        self::assertCount($expectedCountErrors, $errors2);
    }

    public function dataProvider(): array
    {
        return [
            [
                ['val' => (float)23],
                ['val' => ['float']],
                0
            ],
            [
                ['val' => 23],
                ['val' => ['float']],
                1
            ],
            [
                ['val' => 23.0],
                ['val' => ['float']],
                0
            ],
            [
                ['val' => 23.42],
                ['val' => ['float']],
                0
            ],
            [
                ['val' => '23.3'],
                ['val' => ['float']],
                1
            ],
        ];
    }
}
