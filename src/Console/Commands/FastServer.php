<?php
/**
 * Created by PhpStorm.
 * User: Sunny
 * Date: 2018/1/4
 * Time: 下午5:38
 */

namespace Fast\Console\Commands;


use Fast\Http\Server;
use Illuminate\Console\Command;

class FastServer extends Command
{
    /**
     * @var string
     */
    protected $signature = 'fast:server {--start} {--reload} {--stop}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'server base on swoole and laravel';


    protected $server;

    /**
     * FastServer constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $config = config('fast');
        $server = new Server($config);

        if($this -> option('start')) {          //启动
            $this -> info('server is started!');
            $this -> info('server listen to http://' . $config['host'] .':' . $config['port']);
            $server -> start();
        }else if($this -> option('reload')){    //热更新

            $server -> reload();

        }else if($this -> option('stop')){      //停止
            $server -> stop();
            $this -> info('server is stop!');
        }else{
            $this -> error('use server is see [--start|--reload|--stop]');
        }
    }

}