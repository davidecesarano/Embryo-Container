# Embryo Container
Embryo Container is a small and PSR-11 compatible Dependency Injection Container for PHP.

## Requirements
* PHP >= 7.1

## Installation
Using Composer:
```
$ composer require davidecesarano/embryo-container
```

## Usage

### Create Service
Services are defined by anonymous functions that return an instance of an object:
```php
$containerBuilder = new Embryo\Container\ContainerBuilder;
$containerBuilder->set('connection', function(){
    return [
        'host' => 'localhost',
        'db'   => 'database',
        'user' => 'user',
        'pass' => 'pass'
    ];
});

$containerBuilder->set('pdo', function($container){
    $connection = $container->get('connection');
    $mysql = 'mysql:host='.$connection['host'].';dbname='.$connection['db'], $connection['user'], $connection['pass']);
    return new PDO($mysql);
});
```
Note that the anonymous function has access to the current container instance.

### Using Service
Using the defined services:
```php
$container = $containerBuilder->build();
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
$hello = $container->reflection('Hello');
echo $hello->getHello(); // Hello David
```
Embryo Container uses PHP's reflection to detect what parameters a constructor needs. In this example, Embryo Container creates a `Person` instance (if it wasn't already created) and pass it as a constructor parameter.

### Service Provider
Embryo Container can defines a service into a provider extending `ServiceProvider` instance:
```php
  use Embryo\Container\Container;
  use Embryo\Container\ServiceProvider;
  
  class TestServiceProvider extends ServiceProvider 
  {
    public function register()
    {
      $this->container->set('testService', function(){
        return 'This is a Test Service!';
      });
    }
  }
  
  $container = new Container;
  $test_service_provider = new TestServiceProvider($container);
  $test_service_provider->register();
  echo $container['testService']; // This is a Test Service!
```

## Example
You may quickly test this using the built-in PHP server:
```
$ cd example
$ php -S localhost:8000
```
Going to http://localhost:8000.