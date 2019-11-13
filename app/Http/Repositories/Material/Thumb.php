<?php
namespace App\Http\Repositories\Material;

use DB;
use Log;
use App\Utilities\FeedBack;
use App\Models\User\Material\Thumb as Thumbs;

class Thumb
{
    public function list($userId, $appId, $limit)
    {
        return Thumbs::Local($userId)->App($appId)->Recent()->simplePaginate($limit);
    }

    public function get($id, $userId, $appId)
    {
        return Thumbs::Local($userId)->App($appId)->findOrFail($id);
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

        $thumb = $this->get($id, $userId, $appId);
        if ($thumb) {
            if (empty($thumb->media_id)) {
                return Thumbs::where('id', $id)->Local($userId)->App($appId)->delete();
            }

            return FeedBack::MATERIAL_THUMB_CANNOT_DEL;
        }

        return FeedBack::MATERIAL_THUMB_NOT_FOUND;
    }
}
