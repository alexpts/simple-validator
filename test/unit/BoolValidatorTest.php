<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;

class BoolValidatorTest extends TestCase
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
    public function testBoolValidator(array $data, array $rules, int $expectedCountErrors = 0): void
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
                ['required' => 'true'],
                ['required' => ['bool']],
                1
            ],
            [
                ['required' => 'false'],
                ['required' => ['bool']],
                1
            ],
            [
                ['required' => '0'],
                ['required' => ['bool']],
                1
            ],
            [
                ['required' => '1'],
                ['required' => ['bool']],
                1
            ],
            [
                ['required' => true],
                ['required' => ['bool']],
                0
            ],
            [
                ['required' => false],
                ['required' => ['bool']],
                0
            ],
        ];
    }
}
