<?php
    
    /**
     * Container
     * 
     * PSR-11 Container implementation. Container to prepare, manage, and inject 
     * application dependencies.
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
         * Set service.
         *
         * @param string $key 
         * @param callable $resolver 
         */
        public function set(string $key, callable $resolver)
        {
            $this->registry[$key] = call_user_func($resolver, $this);
        }

        /**
         * Get service. 
         *
         * @param string $key 
         * @throws InvalidArgumentException
         * @throws NotFoundException
         * @return mixed 
         */
        public function get($key)
        {
            if(!is_string($key)) {
                throw new \InvalidArgumentException('Key must be a string');
            }

            if (!$this->has($key)) {
                throw new NotFoundException("Service not found");
            }
            return $this->registry[$key];
        }

        /**
         * Returns true if the container can return 
         * an entry the given identifier.
         *
         * @param string $key
         * @throws InvalidArgumentException
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
         * ...
         *
         * @param string $key
         * @throws ContainerException
         * @return mixed
         */
        public function reflection(string $key)
        {
            if ($this->has($key)) {
                return $this->registry[$key];
            }

            $reflected_class = new \ReflectionClass($key);
            if (!$reflected_class->isInstantiable()) {
                throw new ContainerException("$key is not instantiable");
            }
                        
            if ($constructor = $reflected_class->getConstructor()) {
            
                $parameters = $constructor->getParameters();
                $constructor_parameters = [];
                foreach ($parameters as $parameter) {
                    
                    if ($parameter->getClass()) {
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
         * Set inaccessible properties.
         * 
         * @param string $key 
         * @param callable $resolver
         */
        public function __set($key, $resolver)
        {
            $this->set($key, $resolver);
        }
        
        /**
         * Return inaccessible properties.
         * 
         * @param string $key
         * @return mixed
         */
        public function __get($key)
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
         * @param string $key
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
         * @throws InvalidArgumentException
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