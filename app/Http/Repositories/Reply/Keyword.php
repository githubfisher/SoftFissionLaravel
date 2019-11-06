<?php
namespace App\Http\Repositories\Reply;

use DB;
use Log;
use App\Http\Utilities\Constant;
use App\Http\Utilities\FeedBack;
use App\Models\User\Reply\Rules;
use App\Models\User\Reply\Replies;
use App\Models\User\Reply\Keywords;

class Keyword
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
                $ruleId = Rules::insertGetId([
                    'user_id'    => $params['user_id'],
                    'app_id'     => $params['app_id'],
                    'title'      => $params['title'],
                    'reply_rule' => $params['reply_rule'],
                    'start_at'   => $params['start_at'],
                    'end_at'     => $params['end_at'],
                ]);

                data_fill($keywords, '*.rule_id', $ruleId);
                data_fill($replies, '*.rule_id', $ruleId);
                (new Keywords)->addAll($keywords);
                (new Replies())->addAll($replies);
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
                if ($this->diff($rule, $params, ['title', 'reply_rule', 'start_at', 'end_at'])) {
                    Rules::where('id', $id)->update([
                        'title'      => $params['title'],
                        'reply_rule' => $params['reply_rule'],
                        'start_at'   => $params['start_at'],
                        'end_at'     => $params['end_at'],
                    ]);
                }

                $ks    = array_column($rule['keywords'], null, 'id');
                $still = [];
                foreach ($keywords as $k => $keyword) {
                    if (isset($keyword['id']) && $keyword['id'] && $this->diff($ks[$keyword['id']], $keyword, ['keyword', 'match_type'])) {
                        Keywords::where('id', $keyword['id'])->update($keyword);
                        $still[] = $keyword['id'];
                    } else {
                        $keyword['rule_id'] = $id;
                        Keywords::create($keyword);
                    }
                }
                $del = array_diff(array_keys($ks), $still);
                Keywords::whereIn('id', $del)->delete();

                $rp    = array_column($rule['replies'], null, 'id');
                $still = [];
                foreach ($replies as $k => $reply) {
                    if (isset($reply['id']) && $reply['id'] && $this->diff($rp[$reply['id']], $reply, ['difference', 'reply_type', 'reply_type_female', 'content', 'content_female', 'material_id', 'material_id_female'])) {
                        Replies::where('id', $reply['id'])->update($reply);
                        $still[] = $reply['id'];
                    } else {
                        $reply['rule_id'] = $id;
                        Replies::create($reply);
                    }
                }
                $del = array_diff(array_keys($rp), $still);
                Replies::whereIn('id', $del)->delete();
            } catch (\Exception $e) {
                Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                DB::rollBack();
            }

            DB::commit();
        }

        return true;
    }

    private function diff($rule, $params, $columns)
    {
        foreach ($columns as $column) {
            if (isset($rule[$column]) && isset($params[$column]) && $rule[$column] != $params[$column]) {
                return true;
            }
        }

        return false;
    }
}
