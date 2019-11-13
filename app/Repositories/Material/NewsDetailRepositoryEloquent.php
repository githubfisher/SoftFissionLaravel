<?php
namespace App\Repositories\Material;

use App\Entities\Material\MaterialNewsDetail;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class NewsDetailRepositoryEloquent.
 *
 * @package namespace App\Repositories\Material;
 */
class NewsDetailRepositoryEloquent extends BaseRepository implements NewsDetailRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MaterialNewsDetail::class;
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
        return 'App\\Validators\\Material\\NewsDetailValidator';
    }
}
