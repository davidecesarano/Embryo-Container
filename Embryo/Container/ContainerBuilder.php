<?php 

    /**
     * ContainerBuilder
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-container
     */

    namespace Embryo\Container;

    use Embryo\Container\Interfaces\ContainerBuilderInterface;
    use Embryo\Container\Container;
    use Embryo\Container\Exceptions\NotFoundException;
    use Psr\Container\ContainerInterface;

    class ContainerBuilder implements ContainerBuilderInterface, \ArrayAccess
    {
        /**
         * @var array $registry
         */
        private $registry = [];

        /**
         * @var bool $autowiring
         */
        private $autowiring = true;

        /**
         * Set service.
         *
         * @param string $key 
         * @param callable $resolver 
         * @return void
         */
        public function set(string $key, callable $resolver)
        {
            $this->registry[$key] = call_user_func($resolver, $this->build());
        }

        /**
         * Set the ability of the container to automatically 
         * create and inject dependencies in class's
         * constructor.
         * 
         * @param bool $autowiring 
         * @return self
         */
        public function useAutowiring(bool $autowiring = true): self 
        {
            $this->autowiring = $autowiring;
            return $this;
        }

        /**
         * Build container.
         * 
         * @return ContainerInterface
         */
        public function build(): ContainerInterface 
        {
            $container = new Container($this->registry, $this->autowiring);
            return $container;
        }

        /**
         * @param string $key 
         * @return mixed
         */
        public function get(string $key)
        {
            return $this->build()->get($key);
        }

        /**
         * Set service alias.
         * 
         * @param string $key 
         * @param string $keyService
         * @return void
         * @throws NotFoundException
         */
        public function alias(string $key, $keyService)
        {
            if (!isset($this->registry[$keyService])) {
                throw new NotFoundException("$key service not found");
            }
            $this->registry[$key] = $this->registry[$keyService];
        }

        /**
         * ------------------------------------------------------------
         * Property overloading
         * ------------------------------------------------------------
         */

        /**
         * Set inaccessible properties.
         * 
         * @param string $key 
         * @param callable $resolver
         * @return void
         */
        public function __set(string $key, callable $resolver)
        {
            $this->set($key, $resolver);
        }
        
        /**
         * Return inaccessible properties.
         * 
         * @template T
         * @param class-string<T> $key
         * @return mixed
         */
        public function __get(string $key)
        {
            return $this->get($key);
        }        
        
        /**
         * ------------------------------------------------------------
         * ArrayAccess	
         * ------------------------------------------------------------
         */
        
         /**
          * Assign a value to the specified offset.
          *
          * @param string $key
          * @param callable $resolver
          * @return void
          */
        public function offsetSet($key, $resolver) 
        {
            $this->set($key, $resolver);
        }

        /**
         * Offset to retrieve.
         *
         * @template T
         * @param class-string<T> $key
         * @return mixed
         */
        public function offsetGet($key) 
        {
            return $this->get($key);
        }   

        /**
         * Whether an offset exists.
         *
         * @param string $key
         * @return bool
         */
        public function offsetExists($key) 
        {
            return $this->build()->has($key);
        }

        /**
         * Unset an offset.
         *
         * @param string $key
         * @throws \InvalidArgumentException
         * @return void
         */
        public function offsetUnset($key) 
        {
            if(!is_string($key)) {
                throw new \InvalidArgumentException('Key must be a string');
            }
            unset($this->registry[$key]);
        }
    }