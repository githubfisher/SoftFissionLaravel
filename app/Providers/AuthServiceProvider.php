<?php
namespace App\Providers;

use App\Models\User\Reply\Rule;
use App\Models\User\SuperQrCode\QrCode;
use App\Policies\User\Reply\RulePolicy;
use App\Policies\User\SuperQrCode\QrCodePolicy;
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
        Rule::class   => RulePolicy::class,
        QrCode::class => QrCodePolicy::class,
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
