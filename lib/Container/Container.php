<?php

namespace Framework\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    /**
     * Array of service with ['id' => 'classNameSpace']
     * @var array
     */
    private $services = [];

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        if (!$this->has($id)) $this->services[$id] = $id;

        try {
            $reflector = new \ReflectionClass($this->services[$id]);
        } catch (\ReflectionException $e) {
            throw new ContainerException(sprintf('Critical error while trying to reflect class %s', $id));
        }

        if (!$reflector->isInstantiable()) {
            throw new ContainerException("This class: \"$id\" is not instantiable");
        }

        if ($constructor = $reflector->getConstructor()) {

            $params = $constructor->getParameters();

            $resolvedParams = [];

            foreach ($params as $param) {

                if ($paramClass = $param->getClass()) {

                    $resolvedParams[] = $this->get($paramClass->getName());

                } elseif ($param->isDefaultValueAvailable()) {

                    $resolvedParams[] = $param->getDefaultValue();

                } else {
                    throw new NotFoundException(
                        sprintf(
                            'Class: "%s" not found to make instance of %s',
                            $param->getType(),
                            $id
                        )
                    );
                }
            }

            return $reflector->newInstanceArgs($resolvedParams);
        }

        return $reflector->newInstance();
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        if (!is_string($id)) {
            throw new \InvalidArgumentException(sprintf(
                    'Argument must be a string, %s given in Container::has()',
                    is_object($id) ? get_class($id) : gettype($id))
            );
        }

        if (isset($this->services[$id])) return true;

        return false;
    }
}