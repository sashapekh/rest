<?php

namespace Sashapekh\SimpleRest\Core\Uri;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $path;
    private array $serverParams;

    private $schema = '';
    private $port;
    private $host;
    private $protocol;
    private $query;

    public function __construct()
    {
        $this->serverParams = $_SERVER;
        $parsedUrl = parse_url($this->getSrvParam("REQUEST_URI"));
        $this->path = $parsedUrl['path'] ?? '';
        $this->query = $parsedUrl['query'] ?? '';
        $this->schema = 'http://localhost:8080' . $this->path;
        $this->protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
        if ($port = intval($this->getSrvParam('SERVER_PORT'))) {
            $this->port = $port > 0 ? $port : null;
        }
        $this->host = $this->getSrvParam('SERVER_NAME');
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function getAuthority(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getUserInfo(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        return '';
    }

    /**
     * @param string $scheme
     * @return UriInterface
     */
    public function withScheme(string $scheme): UriInterface
    {
        if ($scheme === $this->schema) {
            return $this;
        }
        $new = clone $this;
        $new->schema = $this->schema;
        return $new;
    }

    /**
     * @param string $user
     * @param string|null $password
     * @return UriInterface
     */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        return $this;
    }

    /**
     * @param string $host
     * @return UriInterface
     */
    public function withHost(string $host): UriInterface
    {
        if ($this->host === $host) {
            return $this;
        }
        $new = clone $this;
        $new->host = $host;
        return $new;
    }

    /**
     * @param int|null $port
     * @return UriInterface
     */
    public function withPort(?int $port): UriInterface
    {
        if ($this->port === $port) {
            return $this;
        }

        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    /**
     * @param string $path
     * @return UriInterface
     */
    public function withPath(string $path): UriInterface
    {
        if ($this->path === $path) {
            return $this;
        }
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    /**
     * @param string $query
     * @return UriInterface
     */
    public function withQuery(string $query): UriInterface
    {
        if ($query === $this->query) {
            return $this;
        }
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    /**
     * @param string $fragment
     * @return UriInterface
     */
    public function withFragment(string $fragment): UriInterface
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $port = ($this->getPort() === 80 || is_null($this->getPort()))
            ? ''
            : ":" . $this->getPort();
        $query = empty($this->getQuery())
            ? $this->getQuery()
            : "?" . $this->getQuery();
        return $this->protocol . $this->host . $port . $this->getPath() . $query;
    }

    private function getSrvParam(string $key): string
    {
        return $_SERVER[$key] ?? '';
    }

    public function segments(): ?array
    {
        return parseSegment($this->getPath());
    }
}