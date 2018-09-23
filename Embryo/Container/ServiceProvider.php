<?php 

    /**
     * ServiceProvider
     * 
     * Service providers is a Container Service Definition to register
     * application service.
     * 
     * This can be used in bootstrapping application.
     * 
     * @author Davide Cesarano
     * @link   https://github.com/davidecesarano/embryo-container
     */

    namespace Embryo\Container;

    use Embryo\Container\Interfaces\ServiceProviderInterface;
    use Psr\Container\ContainerInterface;

    abstract class ServiceProvider implements ServiceProviderInterface
    {
        /**
         * @var ContainerInterface $container
         */
        protected $container;

        /**
         * Sets Container.
         *
         * @param ContainerInterface $container
         */
        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register(){}
    }