<?php

use App\Controller\FakeController2;
use Framework\Container\Container;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

class Test1 {
    private $test2;
    /**
     * @var FakeController2
     */
    private $fakeController2;

    public function __construct(Test2 $test2, FakeController2 $fakeController2)
    {
        $this->test2 = $test2;

        $this->fakeController2 = $fakeController2;
    }
}
class Test2 {
    private $id = 0;

    /** @var Test3 */
    private $test3;

    /**
     * Test2 constructor.
     * @param Test3 $test3
     */
    public function __construct(Test3 $test3)
    {
        $this->test3 = $test3;
    }

}

class Test3 {
    private $id = 100;

    /** @var \App\Controller\FakeController1 */
    private $fakeController1;

    /**
     * Test3 constructor.
     * @param \App\Controller\FakeController1 $fakeController1
     */
    public function __construct(\App\Controller\FakeController1 $fakeController1)
    {
        $this->fakeController1 = $fakeController1;
    }

}
class Test4 {
    private $id = 99;
}

$container = new Container();
$test1 = $container->get('Test1');
dump($test1);
dump($container->has('Test3'));
dump($container->get('Test3'));
dump($container->get('Test4'));

//dump(new \Framework\Http\Message());
//$stream = fopen('http://localhost:8001/index.php', 'r');
//$stats = fstat($stream);
//var_dump(stream_get_meta_data($stream));
//$read = fread($stream, 50);
//fclose($stream);
//var_dump($stats);
//var_dump($read);