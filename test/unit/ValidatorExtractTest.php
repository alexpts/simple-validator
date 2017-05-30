<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\Validator\Validator;

class ValidatorExtractTest extends TestCase
{
    /** @var Validator */
    protected $validator;

    public function setUp(): void
    {
        $this->validator = new Validator;
    }

    /**
     * @param array $rule
     * @param array $expected
     *
     * @dataProvider providerData
     */
    public function testExtractArrayRule(array $rule, array $expected): void
    {
        [$name, $params] = $this->validator->extractArrayRule($rule);

        self::assertEquals($name, $expected['name']);
        self::assertEquals($params, $expected['params']);
    }

    /**
     * @param string $rule
     * @param array $expected
     *
     * @dataProvider providerDataSimpleFormat
     */
    public function testExtractStringRule(string $rule, array $expected): void
    {
        [$name, $params] = $this->validator->extractStringRule($rule);

        self::assertEquals($name, $expected['name']);
        self::assertEquals($params, $expected['params']);
    }

    public function providerDataSimpleFormat()
    {
        return [
            [
                'max:8',
                ['name' => 'max', 'params' => [8]]
            ],
            [
                'inArray:Alex:Vova',
                ['name' => 'inArray', 'params' => ['Alex', 'Vova']]
            ],
            [
                'betweenInt:2:20',
                ['name' => 'betweenInt', 'params' => [2, 20]]
            ],
        ];
    }

    public function providerData(): array
    {
        return [
            [
                ['max' => [8]],
                ['name' => 'max', 'params' => [8]]
            ],
            [
                ['inArray' => [['Alex', 'Vova']]],
                ['name' => 'inArray', 'params' => [['Alex', 'Vova']]]
            ],
            [
                ['betweenInt' => [2, 20]],
                ['name' => 'betweenInt', 'params' => [2, 20]]
            ],
        ];
    }
}
