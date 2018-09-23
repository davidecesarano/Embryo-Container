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
    
    use Embryo\Container\Exceptions\ContainerException;
    use Psr\Container\ContainerInterface;
    
    class Container implements ContainerInterface, \ArrayAccess 
    {
        /**
         * @var array $registry
         */
        private $registry = [];
        
        /**
         * Sets service.
         *
         * @param string $key 
         * @param callable $resolver 
         */
        public function set($key, callable $resolver)
        {
            $this->registry[$key] = call_user_func($resolver, $this);
        }

        /**
         * Esegue elemento del container 
         *
         * @param string $key 
         * @throws ContainerException
         * @return mixed 
         */
        public function get($key)
        {
            if ($this->has($key)) {
                return $this->registry[$key];
            }

            $reflected_class = new \ReflectionClass($key);
            if (!$reflected_class->isInstantiable()) {
                throw new ContainerException("$key is not instantiated!");
            }
                        
            if ($constructor = $reflected_class->getConstructor()) {
            
                $parameters = $constructor->getParameters();
                $constructor_parameters = [];
                foreach ($parameters as $parameter) {
                    
                    if ($parameter->getClass()) {
                        $constructor_parameters[] = $this->get($parameter->getClass()->getName());
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
         * Returns true if the container can return an entry the given identifier.
         *
         * @param string $key
         * @return boolean
         */
        public function has($key)
        {
            return isset($this->registry[$key]);
        }

        /**
         * ------------------------------------------------------------
         * Overloading
         * ------------------------------------------------------------
         */
        
        /**
         * Sets inaccessible properties.
         * 
         * @param string $key 
         * @param callable $resolver
         * @example $container['key'] = function() {...}
         */
        public function __set($key, Callable $resolver)
        {
            $this->set($key, $resolver);
        }
        
        /**
         * Returns inaccessible properties.
         * 
         * @param string $key 
         * @example $container['key']
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
        
        public function offsetSet($key, $resolver) 
        {
            $this->set($key, $resolver);
        }

        public function offsetExists($key) {
            return isset($this->registry[$key]);
        }

        public function offsetUnset($key) {
            unset($this->registry[$key]);
        }

        public function offsetGet($key) {
            return isset($this->registry[$key]) ? $this->get($key) : null;
        }        
    }