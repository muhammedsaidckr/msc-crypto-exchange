<?php

namespace Msc\MscCryptoExchange\Objects;

class ApiProxy
{
    /**
     * @var string The host address of the proxy
     */
    public $host;

    /**
     * @var int The port of the proxy
     */
    public $port;

    /**
     * @var string|null The login of the proxy
     */
    public $login;

    /**
     * @var string|null The password of the proxy
     */
    public $password;

    /**
     * ApiProxy constructor.
     *
     * @param  string  $host  The proxy hostname/ip
     * @param  int  $port  The proxy port
     * @param  string|null  $login  The proxy login
     * @param  string|null  $password  The proxy password
     */
    public function __construct(string $host, int $port, ?string $login = null, ?string $password = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->login = $login;
        $this->password = $password;
    }
}
