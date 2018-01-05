<?php

return [
    'worker_num'    => 4,    //worker process num
    'max_request'   => 10000,
    'host'          => '0.0.0.0',
    'port'          => 9101,
    'daemonize'     => false,
    'user'          => 'www',
    'group'         => 'www',
    'pid'           => '/tmp/fast-swoole.pid',
];