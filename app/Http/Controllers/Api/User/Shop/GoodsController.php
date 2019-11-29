<?php
namespace App\Http\Controllers\Api\User\Shop;

use DB;
use Log;
use App\Utilities\FeedBack;
use Illuminate\Support\Arr;
use App\Criteria\MyCriteria;
use App\Entities\Shop\Goods;
use App\Entities\Shop\GoodsBanners;
use App\Http\Controllers\Controller;
use App\Entities\Shop\GoodsPromotions;
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

            foreach ($request->input('banners') as $key => $banner) {
                GoodsBanners::insert([
                    'goods_id'     => $goods->id,
                    'banner_id'    => $banner['id'],
                    'banner_type'  => $banner['type'],
                    'sort'         => $key,
                ]);
            }

            if ($request->has('promotions')) {
                foreach ($request->input('promotions') as $promotion) {
                    GoodsPromotions::insert([
                        'goods_id'     => $goods->id,
                        'promotion_id' => $promotion,
                    ]);
                }
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
     * @param CreateGoodsRequest $request
     * @param                    $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateGoodsRequest $request, $id)
    {
        $this->authorize('update', Goods::class);

        DB::beginTransaction();

        try {
            $this->repository->pushCriteria(MyCriteria::class);
            $goods = $this->repository->with(['banners', 'promotions'])->findOrFail($id);

            $params  = $request->all();
            $goods   = $goods->toArray();
            $columns = app()->make(Goods::class)->getFillable();
            $diff    = array_diff_assoc(Arr::only($params, $columns), Arr::only($goods, $columns));
            if ( ! empty($diff)) {
                $this->repository->update($diff, $id);

                if ($request->has('banners')) {
                    $banners = $request->input('banners');
                    $new     = array_diff(array_column($banners, 'id'), array_column($goods['banners'], 'id'));
                    $del     = array_diff(array_column($goods['banners'], 'id'), array_column($banners, 'id'));
                    if ( ! empty($new)) {
                        $banners = array_column($banners, null, 'id');
                        foreach ($new as $banner) {
                            GoodsBanners::insert([
                                'goods_id'     => $id,
                                'banner_id'    => $banner,
                                'banner_type'  => $banners[$banner]['type'],
                            ]);
                        }
                    }

                    if ( ! empty($del)) {
                        GoodsBanners::where('goods_id', $id)->whereIn('banner_id', $del)->delete();
                    }

                    foreach ($request->input('banners') as $sort => $banner) {
                        GoodsBanners::where(['goods_id' => $id,'banner_id' => $banner])->update(['sort' => $sort]);
                    }
                }

                if ($request->has('promotions')) {
                    $promotions = $request->input('promotions');
                    $new        = array_diff(array_column($promotions, 'id'), array_column($goods['promotions'], 'id'));
                    $del        = array_diff(array_column($goods['promotions'], 'id'), array_column($promotions, 'id'));
                    if ( ! empty($new)) {
                        foreach ($new as $promotion) {
                            GoodsPromotions::insert([
                                'goods_id'     => $id,
                                'promotion_id' => $promotion,
                            ]);
                        }
                    }

                    if ( ! empty($del)) {
                        GoodsPromotions::where('goods_id', $id)->whereIn('promotion_id', $del)->delete();
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return $this->err(FeedBack::UPDATE_FAIL);
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
