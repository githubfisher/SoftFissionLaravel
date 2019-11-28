<?php
namespace App\Providers;

use App\Entities\Shop\Shop;
use App\Entities\Menu\WeMenu;
use App\Entities\Reply\WeRule;
use App\Entities\Shop\Project;
use App\Entities\Material\WeNews;
use App\Entities\QrCode\WeQrcode;
use App\Policies\User\Shop\ShopPolicy;
use App\Policies\User\Shop\ProjectPolicy;
use App\Policies\User\OpenPlatform\Menu\WeMenuPolicy;
use App\Policies\User\OpenPlatform\Reply\WeRulePolicy;
use App\Policies\User\OpenPlatform\Material\WeNewsPolicy;
use App\Policies\User\OpenPlatform\QrCode\WeQrcodePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        WeRule::class   => WeRulePolicy::class,
        WeQrcode::class => WeQrcodePolicy::class,
        WeNews::class   => WeNewsPolicy::class,
        WeMenu::class   => WeMenuPolicy::class,
        Shop::class     => ShopPolicy::class,
        Project::class  => ProjectPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
