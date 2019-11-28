<?php
namespace App\Entities;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    /**
     * 最近的
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecent(Builder $query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * 查询特定账户的
     *
     * @param Builder $query
     * @param $userId
     * @return Builder
     */
    public function scopeUser(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * 查询特定公众号的
     *
     * @param Builder $query
     * @param $appId
     * @return Builder
     */
    public function scopeApp(Builder $query, $appId)
    {
        return $query->where('app_id', $appId);
    }

    /*
     * 批量插入
     */
    public function addAll(array $data)
    {
        $now = Carbon::now()->toDateTimeString();
        data_fill($data, '*.created_at', $now);
        data_fill($data, '*.updated_at', $now);

        return DB::table($this->getTable())->insert($data);
    }

    /*
     * 批量插入
     */
    public function addAllWithoutDataTime(array $data)
    {
        return DB::table($this->getTable())->insert($data);
    }
}
