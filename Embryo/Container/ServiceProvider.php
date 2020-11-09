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

    use Embryo\Container\Interfaces\{ContainerBuilderInterface, ServiceProviderInterface};

    abstract class ServiceProvider implements ServiceProviderInterface
    {
        /**
         * @var ContainerBuilderInterface $container
         */
        protected $container;

        /**
         * Sets Container.
         *
         * @param ContainerBuilderInterface $container
         */
        public function __construct(ContainerBuilderInterface $container)
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