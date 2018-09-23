# Embryo Container
Embryo Container is a small Dependency Injection Container for PHP.

## Requirements
* PHP >= 7.1

## Installation
Using Composer:
```
$ composer require davidecesarano/embryo-middleware
```

## Usage

### Create the Container
Create a Container instance:
```php
use Embryo\Container\Container;

$container = new Container;
```

### Create Service
Services are defined by anonymous functions that return an instance of an object:
```php
$container->set('connection', function(){
  return [
    'host' => 'localhost',
    'db'   => 'database',
    'user' => 'user',
    'pass' => 'pass'
   ];
});

$container->set('pdo', function($container){
  $connection = $container->get('connection');
  $mysql = 'mysql:host='.$connection['host'].';dbname='.$connection['db'], $connection['user'], $connection['pass']);
  return new PDO($mysql);
});
```
Note that the anonymous function has access to the current container instance.

### Using Service
Using the defined services:
```
$pdo = $container->get('pdo');
```

### Automatic Injection
Container can automatically create and inject dependencies:
```php
class Person
{
  public function getName()
  {
    return 'David';
  }
}

class Hello
{
  public $name;
  
  public function __construct(Person $person)
  {
    $this->name = $person->getName();
  }
  
  public function getHello()
  {
    return 'Hello '.$this->name;
  }
}

$container = new \Embryo\Container\Container;
$hello = $container->get('Hello');
echo $hello->getHello(); // Hello David
```
