<?php 

    require __DIR__.'/../vendor/autoload.php';
    
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
    echo $container['testService'];