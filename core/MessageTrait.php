<?php

namespace Sashapekh\SimpleRest\Core;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use Sashapekh\SimpleRest\Core\Stream\Stream;

trait MessageTrait
{
    private string $protocolVersion = '1.1';
    private array $headers = [];
    private array $headerNames = [];


    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     * @return MessageInterface
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        if ($this->protocolVersion === $version) {
            return $this;
        }
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    /**
     * @return \string[][]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    /**
     * @param string $name
     * @return string[]
     */
    public function getHeader(string $name): array
    {
        $header = array_search($name, $this->headerNames);
        if (!isset($this->headerNames[$header])) {
            return [];
        }
        $header = $this->headerNames[$header];

        return [$name => $this->headers[$header]];
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * @param string $name
     * @param $value
     * @return MessageInterface
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $normalized = strtolower($name);

        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $name;
        $new->headers[$name] = $value;

        return $new;
    }

    /**
     * @param string $name
     * @param $value
     * @return MessageInterface|$this
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $normalized = strtolower($name);

        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            $header = $this->headerNames[$normalized];
            $new->headers[$header] = array_merge($this->headers[$header], $value);
        } else {
            $new->headerNames[$normalized] = $name;
            $new->headers[$name] = $value;
        }

        return $new;
    }

    /**
     * @param string $name
     * @return MessageInterface|$this
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $normalized = strtolower($name);

        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }

        $header = $this->headerNames[$normalized];

        $new = clone $this;
        unset($new->headers[$header], $new->headerNames[$normalized]);

        return $new;
    }

    /**
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return (new Stream());
    }

    /**
     * @param StreamInterface $body
     * @return MessageInterface|$this
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = $this;
        $new->body = $body;
        return $new;
    }

    public function isJson(): bool
    {
        if ($this->hasHeader('Content-Type')) {
            $value = $this->getHeader('Content-Type')['Content-Type'] ?? null;
            return $value === 'application/json';
        }
        return false;
    }
}