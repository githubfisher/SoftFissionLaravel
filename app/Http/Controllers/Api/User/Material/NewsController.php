<?php
namespace App\Http\Controllers\Api\User\Material;

use App\Criteria\MyCriteria;
use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Models\User\Material\News as Material;
use App\Http\Requests\User\Material\NewsRequest;
use App\Repositories\Material\NewsRepositoryEloquent;
use App\Http\Requests\User\Material\CreateNewsRequest;

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

    public function index(Request $request)
    {
        $this->authorize('view', Material::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $this->repository->pushCriteria(MyCriteria::class);
        $list  = $this->repository->findWhere(['app_id', $request->input('app_id')])->paginate($limit);

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
        $this->authorize('create', Material::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->repository->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @param NewsRequest $request
     * @param             $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(NewsRequest $request, $id)
    {
        $this->authorize('view', Material::class);

        $data = $this->repository->get($id, $this->user()->id, $request->input('app_id'));

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
        $this->authorize('update', Material::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->repository->update($id, $params);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err($res);
    }

    /**
     * @param NewsRequest $request
     * @param             $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(NewsRequest $request, $id)
    {
        $this->authorize('delete', Material::class);

        $res = $this->repository->destory($id, $this->user()->id, $request->input('app_id'));
        if ($res === true) {
            return $this->suc();
        }

        return $this->err($res);
    }

    // todo 同步图文
}
