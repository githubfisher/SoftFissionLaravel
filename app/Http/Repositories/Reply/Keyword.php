<?php
namespace App\Http\Repositories\AutoReply;

use App\Http\Utilities\Constant;
use App\Models\User\Rule;

class Keyword
{
    public function list($userId, $appId, $scene, $limit = Constant::PAGINATE_MIN)
    {
        return Rule::Local($userId)->App($appId)->Scene($scene)->Recent()->paginate($limit);
    }
}
