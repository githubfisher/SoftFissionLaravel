<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\QrCode;

use Log;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\QrCode\WeQrcode;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Repositories\QrCode\WeQrcodeRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\QrCode\CreateWeQrcodeRequest;
use App\Http\Requests\User\OpenPlatform\QrCode\UpdateWeQrcodeRequest;

/**
 * 超级二维码
 * Class QrCodeController
 * @package App\Http\Controllers\Api\User\SuperQrCode
 */
class WeQrcodeController extends Controller
{
    protected $repository;

    public function __construct(WeQrcodeRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param PaginateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(PaginateRequest $request)
    {
        $this->authorize('view', WeQrcode::class);

        $list  = $this->repository->app($request->input('app_id'))->recent()->paginate($request->input('limit', Constant::PAGINATE_MIN));

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateWeQrcodeRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateWeQrcodeRequest $request)
    {
        $this->authorize('create', WeQrcode::class);

        $params                                                  = $request->all();
        $params['scene']                                         = Constant::REPLY_RULE_SCENE_SCAN;
        list($params['expire_at'], $params['expire_in'], $error) = $this->getExpire($params);
        if ( ! is_null($error)) {
            return $this->err($error);
        }

        $res = $this->repository->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', WeQrcode::class);

        $data = $this->repository->with('details')->find($id);

        return $this->suc(compact('data'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showRule($id)
    {
        $this->authorize('view', WeQrcode::class);

        $data = $this->repository->with('rule.replies')->find($id);

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param UpdateWeQrcodeRequest $request
     * @param                     $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateWeQrcodeRequest $request, $id)
    {
        $this->authorize('update', WeQrcode::class);

        $res = $this->repository->updateQrCode($id, $request->all());
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('delete', WeQrcode::class);

        $res = $this->repository->destroy($id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * 换算-到期时间
     * @param $params
     *
     * @return array
     */
    private function getExpire($params)
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
