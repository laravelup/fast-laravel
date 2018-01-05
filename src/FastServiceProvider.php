<?php

namespace Fast;

use Illuminate\Support\ServiceProvider;

/**
 * Class FastServiceProvider
 * @package Fast
 */
class FastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this -> publishes([
            __DIR__ .'/Config/Fast.php' => config_path('fast.php')
        ]);
    }

    public function register()
    {
        //
    }
}
