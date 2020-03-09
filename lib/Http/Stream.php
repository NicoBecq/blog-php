<?php

namespace Framework\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    /**
     * @var resource
     */
    protected $stream;

    /**
     * Stream constructor.
     * @param  $stream
     * @param string $mode
     */
    public function __construct($stream, $mode = 'r')
    {
        if (is_string($stream)) {
            $this->stream = fopen($stream, $mode);
        } else {
            $this->stream = $stream;
        }
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->getContents();
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        fclose($this->detach());
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        $stream = $this->stream;
        $this->stream = null;
        return $stream;
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        return fstat($this->stream)['size'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        $position = ftell($this->stream);

        if (!$position) throw new \RuntimeException('Can\'t find pointer\'s position.');

        return $position;
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
        return feof($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
        return stream_get_meta_data($this->stream)['seekable'];
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        fseek($this->stream, $offset, $whence);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->seek(0);
    }

    /**
     * @inheritDoc
     */
    public function isWritable()
    {
        $mode = stream_get_meta_data($this->stream)['mode'];

        return in_array($mode, ['w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'r+']);
    }

    /**
     * @inheritDoc
     */
    public function write($string)
    {
        return fwrite($this->stream, $string);
    }

    /**
     * @inheritDoc
     */
    public function isReadable()
    {
        $mode = stream_get_meta_data($this->stream)['mode'];

        return in_array($mode, ['r', 'r+', 'w+', 'a+', 'x+', 'c+']);
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
        return fread($this->stream, $length) ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        $content = stream_get_contents($this->stream);

        if (!$content) throw new \RuntimeException('Unable to read the stream.');

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        $streamData = stream_get_meta_data($this->stream);

        if ($key) {
            return stream_get_meta_data($this->stream)[$key] ?? null;
        }

        return $streamData;
    }
}