<?php
namespace App\Http\Repositories\Material;

use App\Models\User\Material\News as Material;

class News
{
    public function list($userId, $appId, $limit)
    {
        return Material::Local($userId)->App($appId)->Recent()->simplePaginate($limit);
    }

    public function get($id, $userId, $appId)
    {
        return Material::Local($userId)->App($appId)->findOrFail($id);
    }

    public function create()
    {
    }

    public function update()
    {
    }

    public function destory()
    {
    }
}
