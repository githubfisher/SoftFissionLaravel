<?php
namespace App\Providers;

use App\Models\User\Reply\Rule;
use App\Entities\QrCode\WeQrcode;
use App\Models\User\Material\News;
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
        Rule::class     => WeRulePolicy::class,
        WeQrcode::class => WeQrcodePolicy::class,
        News::class     => WeNewsPolicy::class,
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
