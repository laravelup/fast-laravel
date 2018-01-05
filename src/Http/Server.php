<?php
/**
 * Created by PhpStorm.
 * User: Sunny
 * Date: 2018/1/4
 * Time: 下午5:36
 */

namespace Fast\Http;


use Fast\Contracts\ServerInterface;
use Fast\Event\Callback;
use Fast\FastException;

class Server implements ServerInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Server constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this -> config = $config;
    }


    /**
     * start server
     */
    public function start()
    {
        $server = new \Swoole\Http\Server($this -> config['host'],$this -> config['port']);
        $server -> set($this -> config);
        $callback = new Callback($this -> config);
        $server->on('Start', [$callback, 'onStart']);
        $server->on('Connect', [$callback, 'onConnect']);
        $server->on('Receive', [$callback, 'onReceive']);
        $server->on('request',[$callback,'onRequest']);
        $server->on('Task', [$callback, 'onTask']);
        $server->on('Finish', [$callback, 'onFinish']);
        $server->on('WorkerStart', [$callback, 'onWorkerStart']);
        $server->on('ManagerStart', [$callback, 'onManagerStart']);
        $server->on('Close', [$callback, 'onClose']);
        $server -> start();
    }

    /**
     * @throws FastException
     * reload server
     */
    public function reload()
    {
        $pid = file_get_contents($this -> config['pid']);
        if(empty($pid)) {
            throw new FastException('server is not start');
        }
        posix_kill($pid,SIGUSR1);
    }


    /**
     * stop server
     */
    public function stop()
    {
        shell_exec("ps aux | grep fast:server | awk '{ print $2 }' | xargs kill -9");
        @unlink($this -> config['pid']);
    }

}