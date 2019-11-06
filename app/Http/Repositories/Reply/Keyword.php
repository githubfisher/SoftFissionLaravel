<?php
namespace App\Http\Repositories\Reply;

use App\Http\Utilities\Constant;
use App\Models\User\Reply\Rules;

class Keyword
{
    public function list($userId, $appId, $scene, $limit = Constant::PAGINATE_MIN)
    {
        return Rules::Local($userId)->App($appId)->Scene($scene)->Recent()->paginate($limit);
    }
}
