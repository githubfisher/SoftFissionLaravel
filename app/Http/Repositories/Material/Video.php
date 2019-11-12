<?php
namespace App\Http\Repositories\Material;

use DB;
use Log;
use App\Http\Utilities\FeedBack;
use App\Models\User\Material\Video as Videos;

class Video
{
    public function list($userId, $appId, $limit)
    {
        return Videos::Local($userId)->App($appId)->Recent()->simplePaginate($limit);
    }

    public function get($id, $userId, $appId)
    {
        return Videos::Local($userId)->App($appId)->findOrFail($id);
    }

    public function store($params)
    {
        DB::beginTransaction();

        try {
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
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return FeedBack::UPDATE_FAIL;
        }

        DB::commit();

        return true;
    }

    public function destroy($id, $userId, $appId)
    {
        // 引用保护 TODO

        $video = $this->get($id, $userId, $appId);
        if ($video) {
            if (empty($video->media_id)) {
                return Videos::where('id', $id)->Local($userId)->App($appId)->delete();
            }

            return FeedBack::MATERIAL_VIDEO_CANNOT_DEL;
        }

        return FeedBack::MATERIAL_VIDEO_NOT_FOUND;
    }
}
