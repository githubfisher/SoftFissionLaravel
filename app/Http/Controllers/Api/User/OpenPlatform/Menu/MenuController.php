<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Menu;

use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\Reply\WeRule;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMenuRequest;
use App\Repositories\Menu\WeMenuRepositoryEloquent;

class MenuController extends Controller
{
    protected $repository;

    public function __construct(WeMenuRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', WeRule::class);

        $list  = $this->repository->app(current_weapp()['app_id'])->get();

        return $this->suc(compact('list'));
    }

    /**
     * @param CreateMenuRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateMenuRequest $request)
    {
        $this->authorize('create', WeRule::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $params['scene']   = Constant::REPLY_RULE_SCENE_KEYWORD;
        $res               = $this->repository->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err(FeedBack::CREATE_FAIL);
    }

    /**
     * @param CreateMenuRequest $request
     * @param                   $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateMenuRequest $request, $id)
    {
        $this->authorize('update', WeRule::class);

        if ($this->repository->updateMenu($id, $request->all())) {
            return $this->suc();
        }

        return $this->err();
    }
}
