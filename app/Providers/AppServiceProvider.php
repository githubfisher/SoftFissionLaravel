<?php
namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (in_array(app()->environment(), ['local', 'dev'])) {
            // 开发所用扩展包在这里注册
            app()->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            app()->register(\Mnabialek\LaravelSqlLogger\Providers\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
