<?php
namespace App\Http\Controllers\Api\Message;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Message\Mail;

class MailController extends Controller
{
    /**
     * 消息列表 - 分页
     *
     * @param Mail $mail
     *
     * @return mixed
     */
    public function index(Mail $mail)
    {
        $list = $mail->list($this->user()->id);

        return $this->suc(compact('list'));
    }

    /**
     * 获取未读消息的数量
     *
     * @param Mail $mail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread(Mail $mail)
    {
        $unread = $mail->unread($this->user()->id);

        return $this->suc(compact('unread'));
    }

    /**
     * "全部"置已读
     *
     * @param Request $request
     * @param Mail    $mail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setRead(Request $request, Mail $mail)
    {
        $mail->setRead($this->user()->id, $request->input('ids', []));

        return $this->suc();
    }
}
