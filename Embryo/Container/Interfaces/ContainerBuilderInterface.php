<?php 

    /**
     * ContainerBuilderInterface
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-container
     */

    namespace Embryo\Container\Interfaces;

    use Psr\Container\ContainerInterface;

    interface ContainerBuilderInterface 
    {
        /**
         * @param string $key 
         * @param callable $resolver 
         * @return void
         */
        public function set(string $key, callable $resolver);

        /**
         * @return ContainerInterface
         */
        public function build(): ContainerInterface;
    }