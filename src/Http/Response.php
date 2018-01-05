<?php
/**
 * Created by PhpStorm.
 * User: Sunny
 * Date: 2018/1/4
 * Time: 下午8:01
 */

namespace Fast\Http;



use Illuminate\Contracts\Http\Kernel;

class Response
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * Response constructor.
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this -> kernel = $kernel;
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param \Swoole\Http\Response $swooleResponse
     * @return string
     */
    public function create(\Illuminate\Http\Request $request,\Swoole\Http\Response $swooleResponse)
    {
        ob_start();

        $laravelResponse = $this -> kernel -> handle($request);
        $laravelResponse->send();
        $this -> terminate($request,$laravelResponse);
        $content = ob_get_contents();
        ob_end_clean();

        //设置header
        foreach ($laravelResponse->headers->allPreserveCase() as $name => $values) {
            foreach ($values as $value) {
                $swooleResponse -> header($name, $value);
            }
        }

        return $content;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $response
     * 终止中间件
     */
    public function terminate(\Illuminate\Http\Request $request, $response)
    {
        $this -> kernel ->terminate($request, $response);
    }
}