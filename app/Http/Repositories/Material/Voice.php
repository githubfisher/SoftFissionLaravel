<?php
namespace App\Http\Repositories\Material;

use DB;
use Log;
use App\Http\Utilities\FeedBack;
use App\Models\User\Material\Voice as Voices;

class Voice
{
    public function list($userId, $appId, $limit)
    {
        return Voices::Local($userId)->App($appId)->Recent()->simplePaginate($limit);
    }

    public function get($id, $userId, $appId)
    {
        return Voices::Local($userId)->App($appId)->findOrFail($id);
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

        $voice = $this->get($id, $userId, $appId);
        if ($voice) {
            if (empty($voice->media_id)) {
                return Voices::where('id', $id)->Local($userId)->App($appId)->delete();
            }

            return FeedBack::MATERIAL_VOICE_CANNOT_DEL;
        }

        return FeedBack::MATERIAL_VOICE_NOT_FOUND;
    }
}
