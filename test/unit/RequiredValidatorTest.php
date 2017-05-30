<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Validator\Validator;
use PTS\Validator\ValidatorRuleException;

class RequiredValidatorTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator;
    }

    /**
     * @dataProvider dataProviderForRequiredValidator
     */
    public function testRequiredValidator($data, $rules, $expected)
    {
        $errors = $this->validator->validate($data, $rules);
        self::assertCount($expected, $errors);
    }

    public function dataProviderForRequiredValidator(): array
    {
        $rules = [
            'name' => ['required'],
            'age' => ['required'],
            'size' => ['required']
        ];

        return [
            [
                [
                    'name' => 'Alex',
                    'age' => 32,
                    'size' => ['M']
                ],
                $rules,
                0
            ],

            [
                [
                    'name' => '',
                    'age' => null,
                    'size' => []
                ],
                $rules,
                3
            ],
        ];
    }
}
