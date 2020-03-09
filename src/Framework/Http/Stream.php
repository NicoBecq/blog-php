<?php


namespace App\Framework\Http;


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
        }

        $this->stream = $stream;
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
        return fstat($this->stream)['size'];
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        return ftell($this->stream);
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
        $mod = stream_get_meta_data($this->stream)['mode'];

        return in_array($mod, ['w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'r+']);
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
        $mod = stream_get_meta_data($this->stream)['mode'];

        return in_array($mod, ['r', 'r+', 'w+', 'a+', 'x+', 'c+']);
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
        return fread($this->stream, $length);
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        return stream_get_contents($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        return stream_get_meta_data($this->stream)[$key] ?? null;
    }
}