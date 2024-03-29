<?php

namespace RedMoon\TinkerServer;

class Connection
{
    /** @var int */
    protected $socket;

    /** @var string */
    protected $host;

    /**
     * Connection constructor.
     *
     * @param string $host
     */
    public function __construct(string $host)
    {
        $this->host = $host;
    }

    /**
     * Write.
     *
     * @param  array  $namedParameters
     *
     * @return bool
     */
    public function write(array $namedParameters): bool
    {
        if (! $this->socket = $this->socket ?: $this->createSocket()) {
            return false;
        }

        set_error_handler([self::class, 'nullErrorHandler']);

        try {
            $encodedPayload = base64_encode(serialize($namedParameters))."\n";

            if (-1 !== stream_socket_sendto($this->socket, $encodedPayload)) {
                return true;
            }

            stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
            fclose($this->socket);
            $this->socket = $this->createSocket();

            if (-1 !== stream_socket_sendto($this->socket, $encodedPayload)) {
                return true;
            }
        } finally {
            restore_error_handler();
        }

        return false;
    }

    /**
     * nullErrorHandler.
     *
     * @param  $t
     * @param  $m
     *
     * @return void
     */
    private static function nullErrorHandler($t, $m)
    {
        // no-op
    }

    /**
     * Create Socket.
     *
     * @return resource
     */
    private function createSocket()
    {
        set_error_handler([self::class, 'nullErrorHandler']);
        try {
            return stream_socket_client($this->host, $errno, $errstr, 3, STREAM_CLIENT_CONNECT);
        } finally {
            restore_error_handler();
        }
    }
}
