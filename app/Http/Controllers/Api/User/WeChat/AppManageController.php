<?php
namespace App\Http\Controllers\Api\User\WeChat;

use App\Utilities\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\WeChat\AppRequest;
use App\Repositories\WeChat\WeAppRepositoryEloquent;

class AppManageController extends Controller
{
    protected $repository;

    public function __construct(WeAppRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 用户名下公众号列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $list = $this->repository->list($this->user()->id);

        return $this->suc(compact('list'));
    }

    /**
     * 切换公众号
     *
     * @param AppRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function switch(AppRequest $request)
    {
        $res = $this->repository->switchApp($this->user()->id, $request->input('app_id'));

        return $res ? $this->suc() : $this->err(FeedBack::SWITCH_FAIL);
    }

    /**
     * 解绑公众号
     *
     * @param AppRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unbind(AppRequest $request)
    {
        $res = $this->repository->unbind($request->input('app_id'), $this->user()->id);

        return $res ? $this->suc() : $this->err($res);
    }
}
