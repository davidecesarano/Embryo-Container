<?php 

    /**
     * ServiceProviderInterface
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
