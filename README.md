# simple-validator

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/de0407d9-12fe-4d3d-a688-9b29b10a0e46/big.png)](https://insight.sensiolabs.com/projects/de0407d9-12fe-4d3d-a688-9b29b10a0e46)

[![Build Status](https://travis-ci.org/alexpts/simple-validator.svg?branch=master)](https://travis-ci.org/alexpts/simple-validator)
[![Code Coverage](https://scrutinizer-ci.com/g/alexpts/simple-validator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alexpts/simple-validator/?branch=master)
[![Code Climate](https://codeclimate.com/github/alexpts/simple-validator/badges/gpa.svg)](https://codeclimate.com/github/alexpts/simple-validator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/simple-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/simple-validator/?branch=master)


Simple validator for validate accos arrays.


Example:

```php
$body = (array)$this->request->getParsedBody();

$validator = new Validator;

// shot format
$errors = $validator->validate($body, [
    'parentId' => ['int', 'betweenInt:0:99999'],
    'name' => ['string', 'min:3'],
    'slug' => ['string', 'min:3', 'max:120'],
    'sort' => ['int']
]);

// full format
$errors = $validator->validate($body, [
    'parentId' => ['int', ['betweenInt' => [0, 99999]] ],
    'name' => ['string', ['min' => [3]] ],
    'slug' => ['string', ['min' => [3]], ['max' => [120]] ],
    'sort' => ['int']
]);

// validate if exists
$errors = $validator->validateIfExists($body, [
    'name' => ['string', ['min' => [3]] ],
    'sort' => ['int'] // field not passed, not validate
]);


// deep attributes
$errors = $validator->validate($body, [
    'user' => ['array', 'min:3'],
    'user.name' => ['string', 'min:3'],
    'user.age' => ['int'],
]);

$errors = $validator->validateIfExists($body, [
    'user' => ['array', 'min:3'],
    'user.name' => ['string', 'min:3'],
    'user.age' => ['int'],
]);

```


#### Validators:

##### string
The field under validation must be string

##### int
The field under validation must be integer

##### array
The field under validation must be array

##### strictBool
The field under validation must be boolean

##### required
Обязательное значение. Не пустая строка или не пустой массив

##### betweenInt:min:max
Чиcло входит в указанный диапозон [min, max]

##### bool
Yes, 1, true является true (FILTER_VALIDATE_BOOLEAN)

##### alpha
The field under validation must be entirely alphabetic characters

##### alphaDash
The field under validation may have alpha-numeric characters, as well as dashes and underscores

##### alphaNum
The field under validation must be entirely alpha-numeric characters

##### date
The field under validation must be a valid date according to the strtotime PHP function

##### dateTime
Value instance of \DateTime object

##### inArray
Check value in array

##### min
Min length fot string, min value for number, min count for array

##### max
Max length fot string, max value for number, max count for array


#### Custom validator
You can add custom validator via method

```php
public function registerRule(string $name, callable $handler): self
```
