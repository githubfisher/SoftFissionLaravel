<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Material;

use App\Utilities\Constant;
use App\Entities\Material\WeNews;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Repositories\Material\NewsRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\Material\CreateNewsRequest;

/**
 * 图文素材
 * Class NewsController
 * @package App\Http\Controllers\Api\User\Material
 */
class NewsController extends Controller
{
    protected $repository;

    public function __construct(NewsRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param PaginateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(PaginateRequest $request)
    {
        $this->authorize('view', WeNews::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->repository->app(current_weapp()['app_id'])->with(['details'])->paginate($limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateNewsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateNewsRequest $request)
    {
        $this->authorize('create', WeNews::class);

        $params           = $request->all();
        $params['app_id'] = current_weapp()['app_id'];
        $id               = $this->repository->store($params);

        return $this->suc(compact('id'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', WeNews::class);

        $data = $this->repository->app(current_weapp()['app_id'])->with('details')->findOrFail($id);

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param CreateNewsRequest $request
     * @param                   $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateNewsRequest $request, $id)
    {
        $this->authorize('update', WeNews::class);

        $params           = $request->all();
        $params['app_id'] = current_weapp()['app_id'];
        $res              = $this->repository->updateNews($params, $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err($res);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('delete', WeNews::class);

        $res = $this->repository->destory($id, current_weapp()['app_id']);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err($res);
    }

    // todo 同步图文
}
