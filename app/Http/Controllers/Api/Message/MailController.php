<?php
namespace App\Http\Controllers\Api\Message;

use App\Entities\User\User;
use App\Utilities\Constant;
use App\Notifications\Welcome;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaginateRequest;
use App\Repositories\Message\SiteMailRepositoryEloquent;

class MailController extends Controller
{
    protected $repository;

    public function __construct(SiteMailRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 消息列表 - 分页
     *
     * @param PaginateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PaginateRequest $request)
    {
        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $user  = Auth::user();
        $list  = $user->notifications()->paginate($limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    public function store()
    {
        $user = Auth::user();
        $user->notify(new Welcome($user));

        return $this->suc();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    /**
     * 获取未读消息的数量
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unreadCount()
    {
        $user  = Auth::user();
        $count = $user->unreadNotifications()->count();

        return $this->suc(compact('count'));
    }

    /**
     * "全部"置已读
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAllRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return $this->suc();
    }
}
