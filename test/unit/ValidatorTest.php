<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Validator\Validator;
use PTS\Validator\ValidatorRuleException;

class ValidatorTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator;
    }

    public function testGetRules()
    {
        $rules = $this->validator->getRules();

        self::assertGreaterThan(5, count($rules));
        foreach ($rules as $handler) {
            self::assertTrue(is_callable($handler));
        }
    }

    public function testRegisterRule()
    {
        $this->validator->registerRule('someValidator2', function() {
            return true;
        });

        $rules = $this->validator->getRules();

        self::assertArrayHasKey('someValidator2', $rules);
    }

    public function testRunUnknownRule()
    {
        $this->expectException(ValidatorRuleException::class);

        $data = ['age' => 24];
        $this->validator->validate($data, [
            'age' => ['int', 'someValidator']
        ]);
    }

    public function testValidateInt()
    {
        $data = ['age' => 24, 'badAge' => '24'];
        $errors = $this->validator->validate($data, [
            'age' => ['int']
        ]);
        self::assertCount(0, $errors);

        $errors = $this->validator->validate($data, [
            'badAge' => ['int']
        ]);
        self::assertCount(1, $errors);
    }

    public function testValidateBool()
    {
        $data = ['isCheck2' => true];
        $errors = $this->validator->validate($data, [
            'isCheck2' => ['bool'],
        ]);
        self::assertCount(0, $errors);

        $data = ['isCheck3' => 'true', 'isOn' => 'on'];
        $errors = $this->validator->validate($data, [
            'isCheck3' => ['bool'],
            'isOn' => ['bool'],
        ]);
        self::assertCount(0, $errors);
    }

    public function testValidateIfNotPassFields()
    {
        $data = ['name' => 'Alex'];
        $errors = $this->validator->validate($data, [
            'name' => ['string'],
            'age' => ['int', 'betweenInt:1:120'],
        ]);

        self::assertCount(1, $errors);
        self::assertEquals('Value is not exists: age', $errors['age']);
    }

    public function testValidateIfExist()
    {
        $data = ['name' => 'Alex', 'age' => 32];
        $errors = $this->validator->validateIfExists($data, [
            'age' => ['int', 'betweenInt:1:120'],
        ]);

        self::assertCount(0, $errors);
    }

    public function testMinValidator()
    {
        $data = ['name' => 'Alex', 'age' => 32];
        $errors = $this->validator->validateIfExists($data, [
            'age' => ['int', 'betweenInt:1:120'],
        ]);

        self::assertCount(0, $errors);
    }

    /**
     * @dataProvider dataProviderForRequiredValidator
     */
    public function testRequiredValidator($data, $rules, $expected)
    {
        $errors = $this->validator->validate($data, $rules);
        self::assertCount($expected, $errors);
    }
    
    public function dataProviderForRequiredValidator()
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