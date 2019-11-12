<?php
namespace App\Http\Repositories\Material;

use DB;
use Log;
use App\Http\Utilities\FeedBack;
use App\Models\User\Material\NewsDetail;
use App\Models\User\Material\News as Material;

class News
{
    public function list($userId, $appId, $limit)
    {
        return Material::Local($userId)->App($appId)->Recent()->simplePaginate($limit);
    }

    public function get($id, $userId, $appId)
    {
        return Material::with(['details'])->Local($userId)->App($appId)->findOrFail($id);
    }

    public function store($params)
    {
        DB::beginTransaction();

        try {
            $newsId = Material::insertGetId([
                'user_id' => $params['user_id'],
                'app_id'  => $params['app_id'],
            ]);

            data_fill($params['details'], '*.news_id', $newsId);
            (new NewsDetail)->addAll($params['details']);
            // 关联海报, 图片 TODO
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return FeedBack::CREATE_FAIL;
        }

        DB::commit();

        return true;
    }

    public function update()
    {
    }

    public function destory()
    {
    }
}
