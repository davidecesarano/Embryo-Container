<?php 

    require __DIR__.'/../vendor/autoload.php';
    
    use Embryo\Container\ContainerBuilder;
    use Embryo\Container\ServiceProvider;

    class TestServiceProvider extends ServiceProvider 
    {
        public function register()
        {
            $this->container->set('testService', function($container){
                return 'This is a Test Servicesssssss! '.$container->get('testGet');
            });
        }
    }

    $containerBuilder = new ContainerBuilder;
    $containerBuilder->set('testGet', function(){
        return 'This is a testGet!';
    });
    $test_service_provider = new TestServiceProvider($containerBuilder);
    $test_service_provider->register();
    $container = $containerBuilder->build();
    echo $container->get('testService');