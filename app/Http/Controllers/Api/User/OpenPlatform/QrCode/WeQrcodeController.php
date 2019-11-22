<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\QrCode;

use DB;
use Carbon\Carbon;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\QrCode\WeQrcode;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Reply\Rule;
use App\Jobs\User\SuperQrCode\DetailCreateJob;
use App\Models\User\SuperQrCode\QrCode as QrCodes;
use App\Repositories\QrCode\WeQrcodeRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\QrCode\WeQrcodeRequest;
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
     * @param WeQrcodeRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(WeQrcodeRequest $request)
    {
        $this->authorize('view', WeQrcode::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->repository->recent()->app($request->input('app_id'))->simplePaginate($limit);

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

        $params                            = $request->all();
        $params['user_id']                 = $this->user()->id;
        $params['scene']                   = Constant::REPLY_RULE_SCENE_SCAN;
        list($expireAt, $expireIn, $error) = $this->repository->getExpire($params);
        if ( ! is_null($error)) {
            return $this->err($error);
        }

        DB::beginTransaction();

        try {

            // 创建回复规则
            $params['keywords'] = [];
            $params['status']   = Constant::TRUE_ONE;
            $ruleId             = (new Rule)->store($params);

            // 创建二维码记录
            $now      = Carbon::now()->toDateTimeString();
            $qrCodeId = QrCodes::insertGetId([
                'user_id'     => $params['user_id'],
                'app_id'      => $params['app_id'],
                'rule_id'     => $ruleId,
                'title'       => $params['title'],
                'type'        => $params['type'],
                'target_num'  => $params['target_num'],
                'expire_type' => $params['expire_type'],
                'expire_at'   => date('Y-m-d H:i:s', $expireAt),
                'expire_in'   => $expireIn,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // 发布任务-生成微信二维码
            dispatch(new DetailCreateJob([
                'id'          => $qrCodeId,
                'type'        => $params['type'],
                'target_num'  => $params['target_num'],
                'expire_type' => isset($params['expire_type']) ? $params['expire_type'] : Constant::FLASE_ZERO,
                'expire_at'   => $expireAt,
                'expire_in'   => $expireIn,
            ]));
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return FeedBack::CREATE_FAIL;
        }

        DB::commit();

        $res               = $this->repository->store($params);
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

        $res = $this->repository->update($id, $request->all());
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * @param WeQrcodeRequest $request
     * @param               $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(WeQrcodeRequest $request, $id)
    {
        $this->authorize('delete', WeQrcode::class);

        $res = $this->repository->destroy($this->user()->id, $request->input('app_id'), $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }
}
