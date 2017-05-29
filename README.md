# simple-validator

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/de0407d9-12fe-4d3d-a688-9b29b10a0e46/big.png)](https://insight.sensiolabs.com/projects/de0407d9-12fe-4d3d-a688-9b29b10a0e46)

[![Build Status](https://travis-ci.org/alexpts/simple-validator.svg?branch=master)](https://travis-ci.org/alexpts/simple-validator)
[![Code Coverage](https://scrutinizer-ci.com/g/alexpts/simple-validator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alexpts/simple-validator/?branch=master)
[![Code Climate](https://codeclimate.com/github/alexpts/simple-validator/badges/gpa.svg)](https://codeclimate.com/github/alexpts/simple-validator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/simple-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/simple-validator/?branch=master)


Простой валидатор входящих запросов или ассоциативных массивов. Без дополнительных зависимостей.


Example:

```php
$body = (array)$this->request->getParsedBody();

$validator = new Validator;
$errors = $validator->validate($body, [
    'parentId' => ['int', 'betweenInt:0:99999'],
    'name' => ['string', 'min:3'],
    'slug' => ['string', 'min:3', 'max:120'],
    'sort' => ['int']
]);

```
