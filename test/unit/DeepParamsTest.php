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

    public function testDeepValidate()
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

    public function testDeepValidateIfExists()
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