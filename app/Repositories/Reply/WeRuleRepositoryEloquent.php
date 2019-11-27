<?php
namespace App\Repositories\Reply;

use DB;
use Log;
use App\Utilities\Constant;
use Illuminate\Support\Arr;
use App\Entities\Reply\WeRule;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Contracts\CacheableInterface;

/**
 * Class RuleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Reply;
 */
class WeRuleRepositoryEloquent extends BaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WeRule::class;
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
        return 'App\\Validators\\Reply\\WeRuleValidator';
    }

    /**
     * 创建回复规则
     *
     * @param $params
     *
     * @return bool
     */
    public function store($params)
    {
        $keywords = isset($params['keywords']) ? $params['keywords'] : [];
        $replies  = $params['replies'];

        DB::beginTransaction();

        try {
            $rule = $this->create([
                'app_id'     => $params['app_id'],
                'scene'      => $params['scene'],
                'title'      => $params['title'],
                'reply_rule' => $params['reply_rule'],
                'start_at'   => isset($params['start_at']) ? $params['start_at'] : null,
                'end_at'     => isset($params['end_at']) ? $params['end_at'] : null,
                'status'     => isset($params['status']) ? $params['status'] : Constant::FLASE_ZERO,
            ]);

            if (count($keywords)) {
                data_fill($keywords, '*.rule_id', $rule->id);
                app()->make(WeKeywordRepositoryEloquent::class)->addAll($keywords);
            }

            data_fill($replies, '*.rule_id', $rule->id);
            app()->make(WeReplyRepositoryEloquent::class)->addAll($replies);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return false;
        }

        DB::commit();

        return $rule->id;
    }

    /**
     * 更新回复规则
     *
     * @param $id
     * @param $params
     *
     * @return bool
     */
    public function updateRule($id, $params)
    {
        $keywords = isset($params['keywords']) ? $params['keywords'] : [];
        $replies  = $params['replies'];
        $rule     = $this->with(['keywords', 'replies'])->find($id);
        if ( ! $rule) {
            Log::error(__FUNCTION__ . ' rule not found: ' . $id);

            return false;
        }
        $rule = $rule->toArray();

        DB::beginTransaction();

        try {
            // 更新规则表
            $ruleCol  = ['title', 'reply_rule', 'start_at', 'end_at'];
            $ruleInfo = Arr::only($params, $ruleCol);
            $diff     = array_diff_assoc($ruleInfo, Arr::only($rule, array_keys($ruleInfo)));
            ! empty($diff) && $this->update($diff, $id);

            // 更新关键词表
            $keywordRepository = app()->make(WeKeywordRepositoryEloquent::class);
            $ks                = array_column($rule['keywords'], null, 'id');
            if (count($keywords)) {
                $still             = [];
                foreach ($keywords as $k => $keyword) {
                    if (isset($keyword['id']) && $keyword['id']) {
                        $still[] = $keyword['id'];
                        $diff    = array_diff_assoc($keyword, Arr::only($ks[$keyword['id']], ['keyword', 'match_type']));
                        if ( ! empty($diff)) {
                            Log::debug(__FUNCTION__ . ' ' . json_encode($keyword));
                            $keywordRepository->update($keyword, $keyword['id']);
                        }
                    } else {
                        $keyword['rule_id'] = $id;
                        $keywordRepository->create($keyword);
                    }
                }
                // 清理旧关键词
                $del = array_diff(array_keys($ks), $still);
            } else {
                $del = array_keys($ks);
            }
            count($del) && $keywordRepository->deleteWhereIn('id', $del);

            // 更新回复内容表
            $replyRepository = app()->make(WeReplyRepositoryEloquent::class);
            $rp              = array_column($rule['replies'], null, 'id');
            $still           = [];
            foreach ($replies as $k => $reply) {
                if (isset($reply['id']) && $reply['id']) {
                    $still[] = $reply['id'];
                    $diff    = array_diff_assoc($reply, Arr::only($rp[$reply['id']], ['difference', 'reply_type', 'reply_type_female', 'content', 'content_female', 'material_id', 'material_id_female']));
                    if ( ! empty($diff)) {
                        $replyRepository->update($reply, $reply['id']);
                        // 引用计数 TODO
                    }
                } else {
                    $reply['rule_id'] = $id;
                    $replyRepository->create($reply);
                }
            }
            // 清理旧回复内容
            $del = array_diff(array_keys($rp), $still);
            count($del) && $replyRepository->deleteWhereIn('id', $del);
            // 引用计数 TODO
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return false;
        }

        DB::commit();

        return true;
    }

    public function getIdByScene(string $appId, string $scene = Constant::REPLY_RULE_SCENE_ANY)
    {
        $rule = $this->app($appId)->scene($scene)->first(['id']);
        if ($rule) {
            return $rule['id'];
        }

        return false;
    }

    /**
     * 存储-任意回复
     *
     * @param $params
     *
     * @return bool
     */
    public function storeAnyRule($params)
    {
        $params['title']  = '任意回复规则';
        $params['status'] = Constant::TRUE_ONE;

        return $this->store($params);
    }

    /**
     * 存储-关注回复
     *
     * @param $params
     *
     * @return bool
     */
    public function storeSubscribeRule($params)
    {
        $params['title']  = '关注回复规则';
        $params['status'] = Constant::TRUE_ONE;

        return $this->store($params);
    }

    // 点击回复
    // 扫码回复
}
