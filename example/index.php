<?php 

    require __DIR__.'/../vendor/autoload.php';

    class Person
    {
        /**
         * @return string
         */
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

        /**
         * @param Person $person
         */
        public function __construct(Person $person)
        {
            $this->name = $person->getName();
        }

        /**
         * @return string
         */
        public function getHello(): string
        {
            return 'This is a reflection! Hello '.$this->name;
        }
    }

    $containerBuilder = new \Embryo\Container\ContainerBuilder;
    $containerBuilder->set('myService', function(){
        return 'This is myService!<br>';
    });
    $hello = $containerBuilder->get('Hello');
    
    echo $containerBuilder['myService'];
    echo $hello->getHello();
