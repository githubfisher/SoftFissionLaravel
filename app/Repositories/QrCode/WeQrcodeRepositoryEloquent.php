<?php

namespace App\Repositories\QrCode;

use App\Entities\QrCode\WeQrcode;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class QrCodeRepositoryEloquent.
 *
 * @package namespace App\Repositories\QrCode;
 */
class WeQrcodeRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WeQrcode::class;
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
        return 'App\\Validators\\QrCode\\WeQrcodeValidator';
    }

    public function getExpire($params)
    {
        $expireAt = $error = null;
        $expireIn = 0;
        //临时二维码 有效时间换算
        if ($params['type'] == Constant::QR_CODE_TYPE_SHORT_TERM) {
            if ($params['expire_type'] == Constant::QR_CODE_SHORT_TERM_BY_EXPIRE) { // 小时
                $expireAt = strtotime("+ $params[expire_in] hours");
            } else { // 日历
                $expireAt = strtotime($params['expire_at']);
            }

            $expireIn = $expireAt - time();
            if ($expireIn > Constant::CACHE_TTL_THIRTY_DAY) {
                Log::error(__FUNCTION__ . ' 临时二维码有效时长超过30天! ' . json_encode($params));

                $error = FeedBack::PARAMS_INCORRECT;
            }
        }

        return [$expireAt, $expireIn, $error];
    }
}
