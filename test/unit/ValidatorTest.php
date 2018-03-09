<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;
use PTS\Validator\ValidatorRuleException;

class ValidatorTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator(new DeepArray);
    }

    public function testGetRules(): void
    {
        $rules = $this->validator->getRules();

        self::assertGreaterThan(5, \count($rules));
        foreach ($rules as $handler) {
            self::assertInternalType('callable', $handler);
        }
    }

    public function testRegisterRule(): void
    {
        $this->validator->registerRule('someValidator2', function() {
            return true;
        });

        $rules = $this->validator->getRules();

        self::assertArrayHasKey('someValidator2', $rules);
    }

    /**
     * @throws ValidatorRuleException
     */
    public function testRunUnknownRule(): void
    {
        $this->expectException(ValidatorRuleException::class);

        $data = ['age' => 24];
        $this->validator->validate($data, [
            'age' => ['int', 'someValidator']
        ]);
    }

    /**
     * @throws ValidatorRuleException
     */
    public function testValidateInt(): void
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

    /**
     * @throws ValidatorRuleException
     */
    public function testValidateIfNotPassFields(): void
    {
        $data = ['name' => 'Alex'];
        $errors = $this->validator->validate($data, [
            'name' => ['string'],
            'age' => ['int', 'betweenInt:1:120'],
        ]);

        self::assertCount(1, $errors);
        self::assertEquals('Value is not exists or bad: age', $errors['age']);
    }
}