<?php
    
    /**
     * Container
     * 
     * PSR-11 Container implementation. 
     * Finds an entry of the container by its identifier and returns it.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-container
     * @see    https://github.com/php-fig/container/blob/master/src/ContainerInterface.php
     */

    namespace Embryo\Container;
    
    use Embryo\Container\Exceptions\{ContainerException, NotFoundException};
    use Psr\Container\ContainerInterface;
    
    class Container implements ContainerInterface, \ArrayAccess 
    {
        /**
         * @var array $registry
         */
        private $registry = [];
        
        /**
         * @var bool $autowiring
         */
        private $autowiring;

        /**
         * Set service.
         *
         * @param array $registry
         * @param bool $autowiring
         */
        public function __construct(array $registry, bool $autowiring = true)
        {
            $this->registry = $registry;
            $this->autowiring = $autowiring;
        }

        /**
         * Get service. 
         *
         * @template T
         * @param class-string<T> $key 
         * @throws \InvalidArgumentException
         * @return mixed 
         */
        public function get($key)
        {
            if(!is_string($key)) {
                throw new \InvalidArgumentException('Key must be a string');
            }
            return $this->reflection($key);
        }

        /**
         * Returns true if the container can return 
         * an entry the given identifier.
         *
         * @param string $key
         * @throws \InvalidArgumentException
         * @return bool
         */
        public function has($key)
        {
            if(!is_string($key)) {
                throw new \InvalidArgumentException('Key must be a string');
            }
            return isset($this->registry[$key]);
        }

        /**
         * Create and inject dependencies with
         * PHP's reflection.
         *
         * @template T
         * @param class-string<T> $key
         * @throws NotFoundException
         * @throws ContainerException
         * @return mixed
         */
        private function reflection(string $key)
        {
            if ($this->has($key)) {
                return $this->registry[$key];
            }

            if (!$this->autowiring) {
                throw new NotFoundException("Key service not found");
            }

            $reflected_class = new \ReflectionClass($key);
            if (!$reflected_class->isInstantiable()) {
                throw new ContainerException("Key is not instantiable");
            }
                        
            if ($constructor = $reflected_class->getConstructor()) {
            
                $parameters = $constructor->getParameters();
                $constructor_parameters = [];
                foreach ($parameters as $parameter) {
                    
                    if (!is_null($parameter->getClass())) {
                        $constructor_parameters[] = $this->reflection($parameter->getClass()->getName());
                    } else {
                        $constructor_parameters[] = $parameter;
                    }   
                
                }
                $this->registry[$key] = $reflected_class->newInstanceArgs($constructor_parameters);
                
            } else {
                $this->registry[$key] = $reflected_class->newInstance(); 
            }
            return $this->registry[$key];
        }

        /**
         * ------------------------------------------------------------
         * Property overloading
         * ------------------------------------------------------------
         */
        
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
          * @param mixed $key
          * @param mixed $value
          * @return void
          */
        public function offsetSet($key, $value) {}

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
            return $this->has($key);
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