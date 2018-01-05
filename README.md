# fast-laravel
### swoole http-server is running laravel

# 要求
- [x] PHP >= 5.6.4
- [x] swoole >= 1.9.0
- [x] laravel >= 5.5.0
# 安装
```
composer require laravelup/fast-laravel dev-master
```
# 配置
```
// config/app.php 
    'providers'     => [
    
        //add
        Fast\FastServiceProvider::class,
    ]
    

// app/Console/Kernel.php
    protected $commands = [
    
        //add
        \Fast\Console\Commands\FastServer::class,
    
    ]

// 命令行执行  --发布配置文件 fast.php
    php artisan vendor:publish 
```


# 配置文件
```
//和swoole官方文档相同、请参照官方文档
return [
    'worker_num'    => 4,    
    'max_request'   => 10000,
    'host'          => '0.0.0.0',
    'port'          => 9101,
    'daemonize'     => false,
    'user'          => 'www',
    'group'         => 'www',
    'pid'           => '/tmp/fast-swoole.pid',
];
```
 
# nginx
```
location / {
    proxy_pass http://127.0.0.1:9101;
    break;
}
```

# 启动
```
php artiasn fast:server [--start] [--reload] [--stop]
```

# 注意
```
1、php://input 请使用 $GLOBALS['HTTP_RAW_POST_DATA'] 获取
2、暂不能多文件上传、一次只能上传一个、若需要多文件上传需多次请求    
```