<?php
/**
 * Created by PhpStorm.
 * User: Sunny
 * Date: 2018/1/4
 * Time: 下午8:01
 */

namespace Fast\Http;


class Request
{

    /**
     * @var \Swoole\Http\Request
     */
    protected $request;

    /**
     * Request constructor.
     * @param \Swoole\Http\Request $request
     */
    public function __construct(\Swoole\Http\Request $request)
    {
        $this -> request    = $request;
    }

    /**
     * @return \Illuminate\Http\Request
     */
    public function create()
    {
        $get    = isset($this -> request->get) ? $this -> request->get : [];
        $post   = isset($this -> request->post) ? $this -> request->post : [];
        $cookie = isset($this -> request->cookie) ? $this -> request->cookie : [];
        $file   = isset($this -> request->files) ? $this -> request->files : [];
        $server = isset($this -> request->server) ? $this -> request->server : [];
        $header = isset($this -> request->header) ? $this -> request->header : [];

        foreach ($server as $key => $value) {
            $server[strtoupper($key)] = $value;
            unset($server[$key]);
        }

        foreach ($header as $key => $value) {
            $server['HTTP_' . strtoupper($key)] = $value;
        }

        unset($GLOBALS['HTTP_RAW_POST_DATA']);
        $GLOBALS['HTTP_RAW_POST_DATA'] = $this -> request -> rawContent();

        return  \Illuminate\Http\Request::createFromBase(
            (new \Symfony\Component\HttpFoundation\Request($get, $post, [], $cookie, $file, $server))
        );
    }
}