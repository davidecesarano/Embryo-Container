<?php 

    require 'vendor/autoload.php';

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
