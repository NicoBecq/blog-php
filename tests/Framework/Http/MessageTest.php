<?php

namespace Tests\Framework\Http;

use Framework\Http\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testWithProtocolVersionNewInstance()
    {
        $message = new Message();
        $newMessage = $message->withProtocolVersion('1.0');
        $this->assertEquals('1.1', $message->getProtocolVersion());
        $this->assertInstanceOf(Message::class, $newMessage);
        $this->assertEquals('1.0', $newMessage->getProtocolVersion());
    }

//    public function testWithHeader()
//    {
//        $message = new Message();
//        $newMessage = $message->withHeader('Host', '127.0.0.1:8000');
//        $messageMock = $this->createConfiguredMock(Message::class, [''])
//    }
}