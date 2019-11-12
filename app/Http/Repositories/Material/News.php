<?php
namespace App\Http\Repositories\Material;

use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Arr;
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
            $now    = Carbon::now()->toDateTimeString();
            $newsId = Material::insertGetId([
                'user_id'    => $params['user_id'],
                'app_id'     => $params['app_id'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($params['details'] as $key => $detail) {
                $detail['news_id'] = $newsId;
                $detail['sort']    = $key;
                NewsDetail::create($detail);
            }

            // 关联海报, 图片 TODO
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return FeedBack::CREATE_FAIL;
        }

        DB::commit();

        return $newsId;
    }

    public function update($id, $params)
    {
        DB::beginTransaction();

        try {
            $news = $this->get($id, $params['user_id'], $params['app_id']);
            if ($news) {
                $updated = false;
                $still   = [];
                $news    = $news->toArray();
                $details = array_column($news['details'], null, 'id');
                $col     = ['title', 'author', 'digest', 'thumb_url', 'content', 'content_source_url', 'poster_id', 'image_id', 'sort'];
                foreach ($params['details'] as $key => $detail) {
                    $detail['sort'] = $key;
                    if (isset($detail['id']) && $detail['id']) {
                        $data = Arr::only($detail, $col);
                        $diff = array_diff_assoc($data, Arr::only($details[$detail['id']], array_keys($data)));
                        if ( ! empty($diff)) {
                            NewsDetail::where('id', $detail['id'])->update($diff);
                            $updated = true;
                        }
                        $still[] = $detail['id'];
                    } else {
                        $detail['news_id'] = $id;
                        NewsDetail::create($detail);
                        $updated = true;
                    }
                }

                // 清理旧文章 // Todo 引用保护
                $del = array_diff(array_keys($details), $still);
                count($del) && NewsDetail::whereIn('id', $del)->delete();

                // 清理关联海报, 图片关系 TODO

                if ($updated) {
                    Material::where('id', $id)->Local($params['user_id'])->App($params['app_id'])->update([
                        'app_id' => $params['app_id'],
                    ]);
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

    public function destory($id, $userId, $appId)
    {
        // 引用保护 TODO

        $news = $this->get($id, $userId, $appId);
        if ($news) {
            if (empty($news->media_id)) {
                return Material::where('id', $id)->Local($userId)->App($appId)->delete();
            }

            return FeedBack::MATERIAL_NEWS_CANNOT_DEL;
        }

        return FeedBack::MATERIAL_NEWS_NOT_FOUND;
    }
}
