<?php

namespace App\Repositories\Shop;

use App\Entities\Shop\Promotion;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Contracts\CacheableInterface;

/**
 * Class PromotionRepositoryEloquent.
 *
 * @package namespace App\Repositories\Shop;
 */
class PromotionRepositoryEloquent extends BaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Promotion::class;
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
        return 'App\\Validators\\Shop\\PromotionValidator';
    }
}
