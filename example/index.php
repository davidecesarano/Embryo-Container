<?php 

    require __DIR__.'/../vendor/autoload.php';

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
            return 'This is a reflection! Hello '.$this->name;
        }
    }

    $container = new \Embryo\Container\Container;
    
    $container->set('myService', function(){
        return 'This is myService!<br>';
    });

    $hello = $container->reflection('Hello');
    
    echo $container->get('myService');
    echo $hello->getHello();
