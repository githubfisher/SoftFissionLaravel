<?php

namespace App\Repositories\QrCode;

use App\Entities\QrCode\QrCode;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class QrCodeRepositoryEloquent.
 *
 * @package namespace App\Repositories\QrCode;
 */
class QrCodeRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return QrCode::class;
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
        return 'App\\Validators\\QrCode\\QrCodeValidator';
    }
}
