<?php 

    /**
     * ServiceProviderInterface
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-container
     */

    namespace Embryo\Container\Interfaces;

    interface ServiceProviderInterface
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register();
    }
