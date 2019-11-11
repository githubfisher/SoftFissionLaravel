<?php
namespace App\Http\Repositories\SuperQrCode;

use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Http\Utilities\Constant;
use App\Http\Utilities\FeedBack;
use App\Http\Repositories\Reply\Rule;
use App\Jobs\User\SuperQrCode\DetailCreateJob;
use App\Models\User\SuperQrCode\QrCode as QrCodes;

class QrCode
{
    public function list($userId, $appId, $limit)
    {
        return QrCodes::Local($userId)->App($appId)->Recent()->paginate($limit);
    }

    public function get($id)
    {
        return QrCodes::with('rule.replies')->find($id);
    }

    private function getExpire($params)
    {
        $expireAt = $error = null;
        $expireIn = 0;
        //临时二维码 有效时间换算
        if ($params['type'] == 2) {
            if ($params['expire_type'] == 1) { // 小时
                $expireAt = strtotime("+ $params[expire_in] hours");
            } else { // 日历
                $expireAt = strtotime($params['expire_at']);
            }

            $expireIn = $expireAt - time();
            if ($expireIn > 2592000) {
                Log::error(__FUNCTION__ . ' 临时二维码有效时长超过30天! ' . json_encode($params));

                $error = FeedBack::PARAMS_INCORRECT;
            }
        }

        return [$expireAt, $expireIn, $error];
    }

    public function store($params)
    {
        if (is_array($params['keywords']) && is_array($params['replies']) && count($params['replies'])) {
            DB::beginTransaction();

            try {
                list($expireAt, $expireIn, $error) = $this->getExpire($params);
                if ( ! is_null($error)) {
                    return $error;
                }

                // 创建回复规则
                $ruleId = (new Rule)->store($params);

                // 创建二维码记录
                $now      = Carbon::now()->toDateTimeString();
                $qrCodeId = QrCodes::insertGetId([
                    'user_id'     => $params['user_id'],
                    'app_id'      => $params['app_id'],
                    'rule_id'     => $ruleId,
                    'scene'       => $params['scene'],
                    'title'       => $params['title'],
                    'type'        => $params['type'],
                    'target_num'  => $params['target_num'],
                    'expire_type' => $params['expire_type'],
                    'expire_at'   => date('Y-m-d H:i:s', $expireAt),
                    'expire_in'   => $expireIn,
                    'status'      => isset($params) ? $params['status'] : Constant::FLASE_ZERO,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);

                // 发布任务-生成微信二维码
                dispatch(new DetailCreateJob([
                    'id'          => $qrCodeId,
                    'type'        => $params['type'],
                    'target_num'  => $params['target_num'],
                    'expire_type' => isset($params['expire_type']) ? $params['expire_type'] : 1,
                    'expire_at'   => $expireAt,
                    'expire_in'   => $expireIn,
                ]));
            } catch (\Exception $e) {
                Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                DB::rollBack();

                return FeedBack::CREATE_FAIL;
            }

            DB::commit();

            return $qrCodeId;
        }

        return FeedBack::PARAMS_INCORRECT;
    }

    public function update($id, $params)
    {
        if (is_array($params['keywords']) && is_array($params['replies']) && count($params['replies'])) {
            DB::beginTransaction();

            try {
                $qrCode = $this->get($id);
                if ( ! $qrCode) {
                    return FeedBack::RULE_NOT_FOUND;
                }
                $qrCode = $qrCode->toArray();

                // 更新回复规则
                (new Rule())->update($qrCode['rule_id'], $params);

                // 更新二维码头表
                list($expireAt, $expireIn, $error) = $this->getExpire($params);
                if ( ! is_null($error)) {
                    return $error;
                }
                $col               = ['title', 'target_num', 'expire_type'];
                $data              = Arr::only($params, $col);
                $data['expire_at'] = $expireAt;
                $data['expire_in'] = $expireIn;
                $diff              = array_diff_assoc($data, Arr::only($qrCode, array_keys($data)));
                if ( ! empty($diff)) {
                    QrCodes::where('id', $id)->update($diff);

                    if (isset($diff['target_num'])) {
                        // 发布任务-生成微信二维码
                        dispatch(new DetailCreateJob([
                            'id'          => $qrCode['id'],
                            'type'        => $qrCode['type'], // 永久与临时不可修改
                            'target_num'  => $params['target_num'] - $qrCode['target_num'],
                            'expire_type' => isset($params['expire_type']) ? $params['expire_type'] : 1,
                            'expire_at'   => $expireAt,
                            'expire_in'   => $expireIn,
                        ]));
                    }
                }
            } catch (\Exception $e) {
                Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                DB::rollBack();

                return FeedBack::UPDATE_FAIL;
            }

            DB::commit();

            return true;
        }

        return FeedBack::PARAMS_INCORRECT;
    }

    public function destroy($userId, $appId, $id)
    {
        DB::beginTransaction();

        try {
            (new Rule())->destroy($userId, $appId, $id, Constant::REPLY_RULE_SCENE_SCAN);
            QrCodes::Local($userId)->App($appId)->where('id', $id)->delete();
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return FeedBack::UPDATE_FAIL;
        }

        DB::commit();

        return true;
    }
}
