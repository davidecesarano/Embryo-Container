<?php 

    require __DIR__.'/../vendor/autoload.php';
    
    use Embryo\Container\ContainerBuilder;
    use Embryo\Container\ServiceProvider;

    class TestServiceProvider extends ServiceProvider 
    {
        public function register()
        {
            $this->container->set('testService', function($container){
                return 'This is a Test Services! '.$container->get('testGet');
            });
        }
    }

    $containerBuilder = new ContainerBuilder;
    $containerBuilder->set('testGet', function(){
        return 'This is a testGet in service or alias!<br>';
    });
    $test_service_provider = new TestServiceProvider($containerBuilder);
    $test_service_provider->register();
    $containerBuilder->alias('testAlias', 'testGet');

    echo $containerBuilder->get('testService');
    echo $containerBuilder->get('testAlias');