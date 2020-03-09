<?php


namespace App\Framework\Container;


use App\Framework\Parser\YAMLParser;
use phpDocumentor\Reflection\Types\This;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    /**
     * Array of service with ['tags' => 'classNameSpace']
     * @var array
     */
    private $services = [];

    /**
     * @var YAMLParser
     */
    private $yamlParser;

    /**
     * Container constructor.
     * @param YAMLParser $YAMLParser
     */
    public function __construct(YAMLParser $YAMLParser)
    {
        $this->yamlParser = $YAMLParser;
    }


    /**
     * @inheritDoc
     */
    public function get($id)
    {
        if ($this->has($id)) {
            //check if have constructor and args required
            if ($this->hasConstructor($classNamespace = $this->services[$id])) {

                $class = new \ReflectionClass($classNamespace);
                $constructor = $class->getConstructor();
                $args = $constructor->getParameters();

                foreach ($args as $key => $name) {

                    $type = $name->getType(); // get the type of arg

                    foreach ($this->services as $argId => $array) {
                        if ($array['path'] === $type) {
                            $$name = $this->get($argId);
                        }
                    }
                }


            }
        }
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        if (!is_string($id)) throw new \InvalidArgumentException(sprintf('Argument must be a string, %s given in Container::has()', is_object($id) ? get_class($id) : gettype($id)));

        if (array_key_exists($id, $this->services)) return true;

        return false;
    }

    private function setServices()
    {
        $this->services = $this->yamlParser->getContent('services');
    }

    /**
     * Check if a class have constructor
     * @param $className
     * @return bool
     */
    private function hasConstructor($className)
    {
        try {
            $reflectionClass = new \ReflectionClass($className);
            $reflectionClass->getConstructor();

            return true;
        } catch (\ReflectionException $e) {

            return false;
        }
    }
}