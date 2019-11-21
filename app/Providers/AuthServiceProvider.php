<?php
namespace App\Providers;

use App\Models\User\Reply\Rule;
use App\Entities\QrCode\WeQrcode;
use App\Models\User\Material\News;
use App\Policies\User\OpenPlatfrom\Reply\RulePolicy;
use App\Policies\User\OpenPlatfrom\Material\NewsPolicy;
use App\Policies\User\OpenPlatfrom\QrCode\WeQrcodePolicy;
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
        Rule::class     => RulePolicy::class,
        WeQrcode::class => WeQrcodePolicy::class,
        News::class     => NewsPolicy::class,
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
