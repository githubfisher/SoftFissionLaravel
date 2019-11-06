<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    /**
     * 最近的
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeRecent(Builder $query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * 查询特定账户的消息
     *
     * @param $query
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocal($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /*
     * 批量插入
     */
    public function addAll(array $data)
    {
        return DB::table($this->getTable())->insert($data);
    }
}