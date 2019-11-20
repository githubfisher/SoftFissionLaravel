<?php
namespace App\Repositories\WeChat;

use App\Entities\WeChat\WeApp;
use App\Repositories\WeChat\Traits\AppHelper;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Contracts\CacheableInterface;

/**
 * Class AppRepositoryEloquent.
 *
 * @package namespace App\Repositories\WeChat;
 */
class WeAppRepositoryEloquent extends BaseRepository implements CacheableInterface
{
    use CacheableRepository, AppHelper;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WeApp::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {
        return 'App\\Validators\\WeChat\\AppValidator';
    }
}
