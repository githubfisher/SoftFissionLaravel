<?php
namespace App\Http\Repositories\Message;

use App\Http\Utilities\Constant;
use Illuminate\Support\Facades\Redis;
use App\Models\Message\Mail as Message;

class Mail
{
    // 查询未读消息数量
    public function unread(int $userId, string $guard)
    {
        $key = sprintf(Constant::MAIL_UNREAD, $guard, $userId);
        if (Redis::exists($key)) {
            $unread = Redis::get($key);
        } else {
            $unread = Message::Local($userId)->Guard($guard)->Unread()->count();
            Redis::set($key, $unread);
            Redis::expire($key, Constant::CACHE_TTL_TEN_MINUTE);
        }

        return $unread;
    }

    // 消息列表
    public function list(int $userId, string $guard, int $limit = Constant::PAGINATE_MIN)
    {
        return Message::Local($userId)->Guard($guard)->Recent()->paginate($limit);
    }

    // 设置消息已读
    public function setRead(int $userId, string $guard, array $ids = [])
    {
        $query = Message::Local($userId)->Guard($guard)->Unread();
        if (count($ids)) {
            $query = $query->whereIn('id', $ids);
        }
        $res = $query->update(['status' => Constant::TRUE_ONE]);
        if ($res) {
            $key = sprintf(Constant::MAIL_UNREAD, $guard, $userId);
            Redis::del($key);
            if (count($ids)) {
                Redis::set($key, $this->unread($userId, $guard));
            } else {
                Redis::set($key, Constant::FLASE_ZERO);
            }
            Redis::expire($key, Constant::CACHE_TTL_TEN_MINUTE);
        }
    }

    /**
     * 添加消息到消息中心
     * @param $params
     * @return boolean
     * @author fisher
     * @date 2018/11/1 下午7:53
     */
    public static function addMessage(...$params)
    {
        $msg = self::mergeMsg($params);
        if ( ! empty($msg)) {
            if ($id = Message::insertGetId($msg)) {
                return $id;
            }
        }

        return false;
    }

    /**
     * 组合消息
     * @param $params
     * @return array
     */
    public static function mergeMsg($params): array
    {
        if (isset($params[0]) && isset($params[1])) {
            $template = Constant::MAIL_TEMPLATE[$params[1]];
            $data     = [
                'user_id'    => $params[0],
                'scene_code' => $params[1],
                'guard'      => $params[2],
                'title'      => $template[0],
                'content'    => $template[1],
            ];

            $start = 3;
            $count = count($params);
            if ($count > $start) {
                for ($i = $start; $i < $count; $i++) {
                    $data['content'] = sprintf($data['content'], $params[$i]);
                }
            }

            return $data;
        }

        return [];
    }

    /**
     * 批量添加
     * @param array ...$params
     */
    public static function addAll(...$params)
    {
        $userIds = array_shift($params);
        $data    = [];
        foreach ($userIds as $userId) {
            array_unshift($params, $userId);
            $msg = self::mergeMsg(array_values($params));
            if ( ! empty($msg)) {
                $data[] = $msg;
            }
        }

        (new Message())->addAll($data);
    }
}
