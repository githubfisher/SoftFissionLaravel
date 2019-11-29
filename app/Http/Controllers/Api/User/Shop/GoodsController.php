<?php
namespace App\Http\Controllers\Api\User\Shop;

use DB;
use Log;
use App\Utilities\FeedBack;
use Illuminate\Support\Arr;
use App\Criteria\MyCriteria;
use App\Entities\Shop\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Shop\CreateGoodsRequest;
use App\Repositories\Shop\GoodsRepositoryEloquent;

class GoodsController extends Controller
{
    protected $repository;

    public function __construct(GoodsRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param PaginateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(PaginateRequest $request)
    {
        $this->authorize('view', Goods::class);

        $this->repository->pushCriteria(MyCriteria::class);
        $list = $this->repository->paginate($request->input('limit'));

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
     */
    public function store(CreateGoodsRequest $request)
    {
        $this->authorize('create', Goods::class);

        DB::beginTransaction();

        try {
            $params            = $request->all();
            $columns           = app()->make(Goods::class)->getFillable();
            $params['user_id'] = $this->user->id;
            $goods             = $this->repository->create(Arr::only($params, $columns));

            foreach ($request->input('banners') as $banner) {
            }

            if ($request->has('promotions')) {
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return $this->err(FeedBack::CREATE_FAIL);
        }

        DB::commit();

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
        $this->authorize('view', Goods::class);

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
        $this->authorize('update', Goods::class);

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
        $this->authorize('delete', Goods::class);

        $this->repository->pushCriteria(MyCriteria::class);
        if ($res = $this->repository->delete($id)) {
            return $this->suc();
        }

        return $this->err(FeedBack::DELETE_FAIL);
    }
}
