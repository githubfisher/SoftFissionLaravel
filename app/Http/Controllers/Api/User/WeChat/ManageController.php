<?php
namespace App\Http\Controllers\Api\User\WeChat;

use App\Http\Controllers\Controller;
use App\Http\Repositories\WeChatApp\App;
use App\Http\Requests\User\WeChat\AppRequest;

class ManageController extends Controller
{
    public function index(App $apps)
    {
        $list = $apps->list($this->user()->id);

        return $this->suc(compact('list'));
    }

    public function switch(AppRequest $request, App $apps)
    {
        $res = $apps->switchApp($this->user()->id, $request->input('app_id'));

        return $res ? $this->suc() : $this->err($res);
    }

    public function unbind(AppRequest $request, App $apps)
    {
        $res = $apps->unbind($this->user()->id, $request->input('app_id'));

        return $res ? $this->suc() : $this->err($res);
    }
}
