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
            DB::beginTransaction();

            try {
                $ruleId = Rules::where('id', $id)->update([
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
            }

            DB::commit();
        }

        return true;
    }
}
