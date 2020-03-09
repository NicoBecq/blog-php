<?php

namespace Framework\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    /**
     * @var string
     */
    protected $protocolVersion = '1.1';

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * example ['case-insensitive header name' => 'original header name']
     * @var array
     */
    protected $normalizedHeadersName = [];

    /**
     * @var StreamInterface
     */
    protected $body;

    /**
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        $clone = clone $this;
        $clone->setProtocolVersion($version);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        if (array_key_exists(strtolower($name), $this->normalizedHeadersName)) return true;

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        if ($this->hasHeader($name)) return $this->headers[$this->normalizedHeadersName[strtolower($name)]];

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        if ($this->hasHeader($name)) return implode(', ', $this->headers[$this->normalizedHeadersName[strtolower($name)]]);

        return '';
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        if (!is_string($name) || (!is_string($value) && !is_array($value))) throw new \InvalidArgumentException('Header name must be string nor values must be string or array');

        $clone = clone $this;
        $clone->setHeaders([$name => is_array($value) ? $value : [$value]]);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        if (!is_string($name) || (!is_string($value) && !is_array($value))) throw new \InvalidArgumentException('Header name must be string nor values must be string or array');

        $clone = clone $this;
        $clone->setHeaders([$name => $value]);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        $clone = clone $this;

        $headerNameToRemove = $clone->normalizedHeadersName[strtolower($name)];
        unset($clone->headers[$headerNameToRemove]);
        unset($clone->normalizedHeadersName[strtolower($name)]);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    /**
     * Return the protocol number only (ex: "1.1")
     * @param string $protocolVersion
     * @return string
     */
    private function normalizeProtocolVersion(string $protocolVersion): string
    {
        if (!preg_match('/^HTTP/[0-9]\.[0-9]$/', $protocolVersion)) throw new \InvalidArgumentException();

        $normalizedProtocolVersion = explode('/', $protocolVersion);
        return end($normalizedProtocolVersion);
    }

    /**
     * Setter for $protocolVersion
     * @param string $version
     * @return Message
     */
    private function setProtocolVersion(string $version)
    {
        if (!preg_match('/^\d\.\d$/', $version)) {
            $version = $this->normalizeProtocolVersion($version);
        }

        $this->protocolVersion = $version;

        return $this;
    }

    /**
     * Setter for $headers
     * if adding and header exist append value to the existing header
     * @param array $headers
     * @param bool $adding
     */
    private function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            if (!$this->hasHeader($name)) {
                $this->headers[$name] = is_array($value) ? $value : [$value];
                $this->normalizedHeadersName[strtolower($name)] = $name;
            } else {
                $this->headers[$name][] = is_array($value) ? $value : [$value];
            }
        }
    }
}