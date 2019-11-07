<?php
namespace App\Http\Repositories\Reply;

use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Http\Utilities\Constant;
use App\Http\Utilities\FeedBack;
use App\Models\User\Reply\Reply;
use App\Models\User\Reply\Keyword;
use App\Models\User\Reply\Rule as Rules;

class Rule
{
    public function list($userId, $appId, $scene, $limit = Constant::PAGINATE_MIN)
    {
        return Rules::Local($userId)->App($appId)->Scene($scene)->Recent()->paginate($limit);
    }

    public function get($id)
    {
        return Rules::with(['keywords', 'replies'])->find($id);
    }

    public function store($params)
    {
        // {keyword:, match_type:}
        $keywords = $params['keywords'];
        // {difference:, reply_type:, reply_type_female:, content:, content_female:, material_id:, material_id_female}
        $replies  = $params['replies'];
        if (is_array($keywords) && is_array($replies) && count($keywords) && count($replies)) {
            DB::beginTransaction();

            try {
                $now    = Carbon::now()->toDateTimeString();
                $ruleId = Rules::insertGetId([
                    'user_id'    => $params['user_id'],
                    'app_id'     => $params['app_id'],
                    'title'      => $params['title'],
                    'reply_rule' => $params['reply_rule'],
                    'start_at'   => isset($params['start_at']) ? $params['start_at'] : null,
                    'end_at'     => isset($params['end_at']) ? $params['end_at'] : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                data_fill($keywords, '*.rule_id', $ruleId);
                data_fill($replies, '*.rule_id', $ruleId);
                (new Keyword)->addAll($keywords);
                (new Reply())->addAll($replies);
            } catch (\Exception $e) {
                Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                DB::rollBack();

                return FeedBack::CREATE_FAIL;
            }

            DB::commit();

            return $ruleId;
        }

        return FeedBack::PARAMS_INCORRECT;
    }

    public function update($id, $params)
    {
        // {keyword:, match_type:}
        $keywords = $params['keywords'];
        // {difference:, reply_type:, reply_type_female:, content:, content_female:, material_id:, material_id_female}
        $replies  = $params['replies'];
        if (is_array($keywords) && is_array($replies) && count($keywords) && count($replies)) {
            $rule = $this->get($id);
            if ( ! $rule) {
                return FeedBack::RULE_NOT_FOUND;
            }
            $rule = $rule->toArray();

            DB::beginTransaction();

            try {
                // 更新规则表
                $ruleCol  = ['title', 'reply_rule', 'start_at', 'end_at'];
                $ruleInfo = Arr::only($params, $ruleCol);
                $diff     = array_diff_assoc($ruleInfo, Arr::only($rule, array_keys($ruleInfo)));
                ! empty($diff) && Rules::where('id', $id)->update($diff);

                // 更新关键词表
                $ks    = array_column($rule['keywords'], null, 'id');
                $still = [];
                foreach ($keywords as $k => $keyword) {
                    if (isset($keyword['id']) && $keyword['id']) {
                        $still[] = $keyword['id'];
                        $diff    = array_diff_assoc($keyword, Arr::only($ks[$keyword['id']], ['keyword', 'match_type']));
                        if ( ! empty($diff)) {
                            Keyword::where('id', $keyword['id'])->update($keyword);
                        }
                    } else {
                        $keyword['rule_id'] = $id;
                        Keyword::create($keyword);
                    }
                }
                // 清理旧关键词
                $del = array_diff(array_keys($ks), $still);
                Keyword::whereIn('id', $del)->delete();

                // 更新回复内容表
                $rp    = array_column($rule['replies'], null, 'id');
                $still = [];
                foreach ($replies as $k => $reply) {
                    if (isset($reply['id']) && $reply['id']) {
                        $still[] = $reply['id'];
                        $diff    = array_diff_assoc($reply, Arr::only($rp[$reply['id']], ['difference', 'reply_type', 'reply_type_female', 'content', 'content_female', 'material_id', 'material_id_female']));
                        if ( ! empty($diff)) {
                            Reply::where('id', $reply['id'])->update($reply);
                            // 引用计数 TODO
                        }
                    } else {
                        $reply['rule_id'] = $id;
                        Reply::create($reply);
                    }
                }
                // 清理旧回复内容
                $del = array_diff(array_keys($rp), $still);
                Reply::whereIn('id', $del)->delete();
                // 引用计数 TODO
            } catch (\Exception $e) {
                Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                DB::rollBack();
            }

            DB::commit();
        }

        return true;
    }

    public function destroy($userId, $appId, $id)
    {
        return Rules::Local($userId)->App($appId)->delete($id);
    }
}
