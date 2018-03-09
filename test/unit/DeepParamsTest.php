<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;

class DeepParamsTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator(new DeepArray);
    }

    /**
     * @throws \PTS\Validator\ValidatorRuleException
     */
    public function testDeepValidate(): void
    {
        $data = ['user' => [
           'age' => 24,
           'name' => 'Alex'
        ]];

        $errors = $this->validator->validate($data, [
            'user.age' => ['int'],
            'user.name' => ['string'],
        ]);

        $errors2 = $this->validator->validate($data, [
            'user.age' => ['string'],
            'user.name' => ['int'],
        ]);

        self::assertCount(0, $errors);
        self::assertCount(2, $errors2);
    }

    /**
     * @throws \PTS\Validator\ValidatorRuleException
     */
    public function testDeepValidateIfExists(): void
    {
        $data = ['user' => [
            'name' => 'Alex'
        ]];

        $errors = $this->validator->validateIfExists($data, [
            'user.age' => ['int'],
            'user.name' => ['string'],
        ]);

        $errors2 = $this->validator->validateIfExists($data, [
            'user.age' => ['string'],
            'user.name' => ['int'],
        ]);

        self::assertCount(0, $errors);
        self::assertCount(1, $errors2);
    }
}