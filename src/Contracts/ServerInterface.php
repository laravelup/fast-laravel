<?php
/**
 * Created by PhpStorm.
 * User: Sunny
 * Date: 2018/1/4
 * Time: 下午7:59
 */

namespace Fast\Contracts;


interface ServerInterface
{
    /**
     * ServerInterface constructor.
     * @param array $config
     */
    public function __construct(array $config);

    /**
     * @return mixed
     * 启动
     */
    public function start();

    /**
     * @return mixed
     * 热更新
     */
    public function reload();

    /**
     * @return mixed
     * 停止
     */
    public function stop();
}