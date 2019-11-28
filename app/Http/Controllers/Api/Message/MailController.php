<?php
namespace App\Http\Controllers\Api\Message;

use App\Entities\User\User;
use App\Utilities\Constant;
use App\Criteria\MyCriteria;
use App\Notifications\Welcome;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Message\SetReadRequest;
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

    /**
     * 获取未读消息的数量
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function unread()
    {
        $this->repository->pushCriteria(MyCriteria::class);
        $unread = $this->repository->guard(config('auth.defaults.guard'))->unread()->count();

        return $this->suc(compact('unread'));
    }

    /**
     * "全部"置已读
     *
     * @param SetReadRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function setRead(SetReadRequest $request)
    {
        $this->repository->pushCriteria(MyCriteria::class);
        $this->repository->guard(config('auth.defaults.guard'))->updateWhere(['id', $request->input('ids')], ['status' => Constant::TRUE_ONE]);

        return $this->suc();
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
}
