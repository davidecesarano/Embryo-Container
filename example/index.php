<?php 

    require __DIR__.'/../vendor/autoload.php';

    class Person
    {
        public function getName(): string
        {
            return 'David';
        } 
    }

    class Hello
    {
        /**
         * @var string $name
         */
        public $name;

        public function __construct(Person $person)
        {
            $this->name = $person->getName();
        }

        public function getHello(): string
        {
            return 'This is a reflection! Hello '.$this->name;
        }
    }

    $containerBuilder = new \Embryo\Container\ContainerBuilder;
    $containerBuilder->set('myService', function(){
        return 'This is myService!<br>';
    });
    $container = $containerBuilder->build();

    $hello = $container->get('Hello');
    
    echo $container->get('myService');
    echo $hello->getHello();
