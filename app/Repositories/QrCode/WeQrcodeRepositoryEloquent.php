<?php
namespace App\Repositories\QrCode;

use DB;
use Log;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use Illuminate\Support\Arr;
use App\Entities\QrCode\WeQrcode;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Reply\WeReplyRepositoryEloquent;
use App\Jobs\User\OpenPlatform\QrCode\WeQrCodeDetailCreateJob;

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

    /**
     * @param $params
     *
     * @return bool
     * @throws \Exception
     */
    public function store($params)
    {
        DB::beginTransaction();

        try {
            // 创建回复规则
            $params['keywords'] = [];
            $params['status']   = Constant::TRUE_ONE;
            $ruleId             = app()->make(WeReplyRepositoryEloquent::class)->store($params);

            // 创建二维码记录
            $qrCode = $this->create([
                'app_id'      => $params['app_id'],
                'rule_id'     => $ruleId,
                'title'       => $params['title'],
                'type'        => $params['type'],
                'target_num'  => $params['target_num'],
                'expire_type' => $params['expire_type'],
                'expire_at'   => date('Y-m-d H:i:s', $params['expire_at']),
                'expire_in'   => $params['expire_in'],
            ]);

            // 发布任务-生成微信二维码
            dispatch(new WeQrCodeDetailCreateJob([
                'appInfo'     => current_weapp(),
                'id'          => $qrCode->id,
                'type'        => $params['type'],
                'target_num'  => $params['target_num'],
                'expire_type' => isset($params['expire_type']) ? $params['expire_type'] : Constant::FLASE_ZERO,
                'expire_at'   => $params['expire_at'],
                'expire_in'   => $params['expire_in'],
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return false;
        }

        DB::commit();

        return $qrCode->id;
    }

    /**
     * @param array $id
     * @param       $params
     *
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function updateQrCode($id, $params)
    {
        DB::beginTransaction();

        try {
            $qrCode = $this->app(current_weapp()['app_id'])->find($id);
            if ( ! $qrCode) {
                Log::error(__FUNCTION__ . ' qrcode not found: ' . $id);

                return false;
            }
            $qrCode = $qrCode->toArray();

            // 更新回复规则
            $params['keywords'] = [];
            app()->make(WeReplyRepositoryEloquent::class)->update($params, $qrCode['rule_id']);

            // 更新二维码头表
            $col               = ['title', 'target_num', 'expire_type'];
            $data              = Arr::only($params, $col);
            $data['expire_at'] = $params['expire_at'];
            $data['expire_in'] = $params['expire_in'];
            $diff              = array_diff_assoc($data, Arr::only($qrCode, array_keys($data)));
            if ( ! empty($diff)) {
                $this->update($diff, $id);

                if (isset($diff['target_num'])) {
                    // 发布任务-生成微信二维码
                    dispatch(new DetailCreateJob([
                        'id'          => $qrCode['id'],
                        'type'        => $qrCode['type'], // 永久与临时不可修改
                        'target_num'  => $params['target_num'] - $qrCode['target_num'],
                        'expire_type' => isset($params['expire_type']) ? $params['expire_type'] : Constant::FLASE_ZERO,
                        'expire_at'   => $params['expire_at'],
                        'expire_in'   => $params['expire_in'],
                    ]));
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return FeedBack::UPDATE_FAIL;
        }

        DB::commit();

        return true;
    }

    /**
     * @param $id
     *
     * @return array|bool
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();

        try {
            $appId = current_weapp()['app_id'];
            app()->make(WeReplyRepositoryEloquent::class)->app($appId)->scene(Constant::REPLY_RULE_SCENE_SCAN)->delete($id);
            $this->app($appId)->delete($id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return FeedBack::UPDATE_FAIL;
        }

        DB::commit();

        return true;
    }
}
