<?php

namespace Framework\Container;

use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /** @var Container */
    private $container;

    protected function setUp(): void
    {
        $this->container = new class extends Container {

        };
    }


    public function testHas()
    {
        $this->assertFalse($this->container->has('test'));
        dump($this->container);
        $this->assertTrue($this->container->has('YAMLParser'));
    }

}