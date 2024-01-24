<?php

namespace App\Libs;

use Exception;
use DateTimeImmutable;

class Ping
{
    private $host = null;
    private $icmpCode = -1;
    private $attempts = 3;

    public function __construct(string $host)
    {
        $this->host = $host;
        $this->icmpCode = getprotobyname("icmp");
    }

    public function maxAttempts(int $attempts)
    {
        $this->attempts = $attempts;
        return $this;
    }

    public function run()
    {
        $socket  = socket_create(AF_INET, SOCK_RAW, $this->icmpCode);

        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, [ "sec"=> 1, "usec"=> 0 ]);
        socket_connect($socket, $this->host, 0);

        $sent = 0;
        $received = 0;
        $lost = 0;

        $initial_time_in_milliseconds = (new DateTimeImmutable())->format("Uv");

        for($i = 0; $i < $this->attempts; $i++):
            $sent++;

            $package  = "\x08\x00\x19\x2f\x00\x00\x00\x00\x70\x69\x6e\x67";
            socket_send($socket, $package, strlen($package), 0);

            try {

                socket_read($socket, 255);
                $received++;

                break;

            } catch(Exception $e) {

                $lost++;

                if( $i < ($this->attempts - 1) ) {
                    sleep(1);
                }

            }
        endfor;

        $end_time_in_milliseconds = (new DateTimeImmutable())->format("Uv");

        $latency = $end_time_in_milliseconds - $initial_time_in_milliseconds;

        socket_close($socket);

        return compact("sent", "received", "lost", "latency");
    }

}