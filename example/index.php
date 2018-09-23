<?php 

    require 'vendor/autoload.php';

    $container = new \Embryo\Container\Container;

    class Person {
        public function getName()
        {
            return 'Davide';
        }
    }

    class Hello 
    {
        public function __construct(Person $name)
        {
            $this->name = $name->getName();
        }

        public function getHello()
        {
            return 'Hello '.$this->name.'!';
        }
    }

    $hello = $container->get('Hello');
    echo $hello->getHello();