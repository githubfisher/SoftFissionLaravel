<?php

namespace App\Repositories\Material;

use App\Entities\Material\Image;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ImageRepositoryEloquent.
 *
 * @package namespace App\Repositories\Material;
 */
class ImageRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Image::class;
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
        return 'App\\Validators\\Material\\ImageValidator';
    }
}
