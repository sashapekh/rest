<?php

namespace Sashapekh\SimpleRest\Core\Request;

class RequestHelper
{
    private static ?string $userAgent;
    private static ?string $userPlatform;

    private static ?string $requestMethod;

    private static ?string $serverName;

    private static ?string $serverProtocol;

    private static ?string $serverPort;

    private static ?string $httpAcceptEncoding;

    private static ?string $httpAcceptLanguage;

    private static ?string $httpAccept;

    private static ?string $requestUri;

    public function make(): void
    {
        self::$userAgent = $this->getServerParam('HTTP_USER_AGENT');
        self::$requestUri = $this->getServerParam("REQUEST_URI");
        self::$userPlatform = $this->getServerParam('HTTP_SEC_CH_UA_PLATFORM');
        self::$requestMethod = $this->getServerParam('REQUEST_METHOD');
        self::$serverName = $this->getServerParam('SERVER_NAME');
        self::$serverProtocol = $this->getServerParam('SERVER_PROTOCOL');
        self::$serverPort = $this->getServerParam('SERVER_PORT');
        self::$httpAcceptEncoding = $this->getServerParam('HTTP_ACCEPT_ENCODING');
        self::$httpAcceptLanguage = $this->getServerParam('HTTP_ACCEPT_LANGUAGE');
        self::$httpAccept = $this->getServerParam('HTTP_ACCEPT');
    }

    /**
     * @return string|null
     */
    public static function getRequestUri(): ?string
    {
        return self::$requestUri;
    }

    private function getServerParam(string $paramKey): ?string
    {
        return $_SERVER[$paramKey] ?? null;
    }

    /**
     * @return string|null
     */
    public static function getUserAgent(): ?string
    {
        return self::$userAgent;
    }

    /**
     * @return string|null
     */
    public static function getHttpAccept(): ?string
    {
        return self::$httpAccept;
    }

    /**
     * @return string|null
     */
    public static function getUserPlatform(): ?string
    {
        return self::$userPlatform;
    }

    /**
     * @return string|null
     */
    public static function getRequestMethod(): ?string
    {
        return self::$requestMethod;
    }

    /**
     * @return string|null
     */
    public static function getServerName(): ?string
    {
        return self::$serverName;
    }

    /**
     * @return string|null
     */
    public static function getServerProtocol(): ?string
    {
        return self::$serverProtocol;
    }

    /**
     * @return string|null
     */
    public static function getServerPort(): ?string
    {
        return self::$serverPort;
    }

    /**
     * @return string|null
     */
    public static function getHttpAcceptEncoding(): ?string
    {
        return self::$httpAcceptEncoding;
    }

    /**
     * @return string|null
     */
    public static function getHttpAcceptLanguage(): ?string
    {
        return self::$httpAcceptLanguage;
    }

}