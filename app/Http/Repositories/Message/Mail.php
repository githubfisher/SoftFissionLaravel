<?php
namespace App\Http\Repositories\Message;

use App\Http\Utilities\Constant;
use Illuminate\Support\Facades\Redis;
use App\Models\User\Message\Mail as Message;

class Mail
{
    // 查询未读消息数量
    public function unread(int $userId)
    {
        $key = sprintf(Constant::MAIL_UNREAD, $userId);
        if (Redis::exists($key)) {
            $unread = Redis::get($key);
        } else {
            $unread = Message::Local($userId)->Unread()->count();
        }

        return $unread;
    }

    // 消息列表
    public function list(int $userId, int $limit = Constant::PAGINATE_MIN)
    {
        return Message::Local($userId)->paginate($limit);
    }

    // 设置消息已读
    public function setRead(int $userId, array $ids = [])
    {
        $query = Message::Local($userId)->Unread();
        if (count($ids)) {
            $query = $query->whereIn('id', $ids);
        }
        $res = $query->update(['status' => Constant::TRUE_ONE]);
        if ($res) {
            $key = sprintf(Constant::MAIL_UNREAD, $userId);
            Redis::del($key);
            if (count($ids)) {
                Redis::set($key, $this->unread($userId));
            } else {
                Redis::set($key, Constant::FLASE_ZERO);
            }
            Redis::expire($key, Constant::CACHE_TTL_TEN_MINUTE);
        }
    }
}
