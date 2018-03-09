<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Tools\DeepArray;
use PTS\Validator\Validator;
use PTS\Validator\ValidatorRuleException;

class IntValidatorTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator(new DeepArray);
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
}
