<?php
/**
 * Created by PhpStorm.
 * User: Sunny
 * Date: 2018/1/4
 * Time: 下午7:25
 */

namespace Fast\Event;
use Fast\FastException;
use Fast\Http\Request;
use Fast\Http\Response;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request as LaravelRequest;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
use Swoole\Server;


class Callback
{


    /**
     * @var \App\Http\Kernel
     */
    protected $kernel;

    /**
     * @var
     */
    protected $pid;


    /**
     * Callback constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this -> pid = $config['pid'];
    }


    /**
     * @param Server $server
     * @throws FastException
     */
    public function onStart(Server $server)
    {
        $pid    = getmypid();
        $result = file_put_contents($this -> pid,$pid);
        if(!$result) {
            throw new FastException('pid is not write');
        }
    }


    public function onConnect()
    {

    }


    /**
     * @param SwooleRequest $swooleRequest
     * @param SwooleResponse $swooleResponse
     */
    public function onRequest(SwooleRequest $swooleRequest,SwooleResponse $swooleResponse)
    {
        LaravelRequest::enableHttpMethodParameterOverride();
        $laravelRequest     = (new Request($swooleRequest)) -> create();
        $uri                = $swooleRequest -> server['request_uri'];
        if($uri == '/favicon.ico') {
            $swooleResponse -> end('favicon.ico');
        }else{
            $content            = (new Response($this -> kernel)) -> create($laravelRequest,$swooleResponse);
            $swooleResponse -> end($content);
        }
    }

    public function onReceive()
    {

    }


    public function onTask()
    {

    }


    public function onFinish()
    {

    }


    /**
     * @param Server $server
     */
    public function onWorkerStart(Server $server)
    {
        if(function_exists('opcache_reset')) {
            opcache_reset();
        }

        $path           = base_path('bootstrap');
        require_once $path . '/autoload.php';
        $app            = require $path . '/app.php';
        $this -> kernel = $app -> make(Kernel::class);
        echo date('Y-m-d H:i:s') . PHP_EOL;
    }

    public function onManagerStart()
    {

    }


    public function onClose()
    {

    }


}