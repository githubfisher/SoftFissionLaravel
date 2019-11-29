<?php
namespace App\Http\Controllers\Api\User\Shop;

use App\Utilities\FeedBack;
use App\Criteria\MyCriteria;
use Illuminate\Http\Request;
use App\Entities\Shop\Promotion;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Repositories\Shop\ShopRepositoryEloquent;
use App\Repositories\Shop\PromotionRepositoryEloquent;

class PromotionController extends Controller
{
    protected $repository;

    public function __construct(PromotionRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param PaginateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(PaginateRequest $request)
    {
        $this->authorize('view', Promotion::class);

        $shopRepository = app()->make(ShopRepositoryEloquent::class);
        $shopRepository->pushCriteria(MyCriteria::class);
        $shops = $shopRepository->get(['id']);
        if ( ! $shops->isEmpty()) {
            $list = $this->repository->shops(array_column($shops, 'id'))->paginate($request->input('limit'));
        }

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateGoodsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateGoodsRequest $request)
    {
        $this->authorize('create', Promotion::class);

        $params            = $request->all();
        $params['user_id'] = $this->user->id;
        $goods             = $this->repository->create($params);

        return $this->suc(['id' => $goods->id]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show($id)
    {
        $this->authorize('view', Promotion::class);

        $this->repository->pushCriteria(MyCriteria::class);
        $data = $this->repository->find($id);

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', Promotion::class);

        $this->repository->pushCriteria(MyCriteria::class);
        $goods = $this->repository->findOrFail($id);

        $params = $request->all();
        $goods  = $goods->toArray();
        $diff   = array_diff_assoc($params, $goods);
        if ( ! empty($diff)) {
            if ($res = $this->repository->update($diff, $id)) {
                return $this->suc();
            }
        }

        return $this->err(FeedBack::UPDATE_FAIL);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy($id)
    {
        $this->authorize('delete', Promotion::class);

        $this->repository->pushCriteria(MyCriteria::class);
        if ($res = $this->repository->delete($id)) {
            return $this->suc();
        }

        return $this->err(FeedBack::DELETE_FAIL);
    }
}
