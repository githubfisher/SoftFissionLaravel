<?php
namespace App\Http\Controllers\Api\User\Shop;

use DB;
use Log;
use App\Entities\Shop\Shop;
use App\Utilities\FeedBack;
use Illuminate\Support\Arr;
use App\Criteria\MyCriteria;
use App\Entities\Shop\ShopsBrands;
use App\Entities\Shop\ShopsProjects;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CreateShopRequest;
use App\Repositories\Shop\ShopRepositoryEloquent;

class ShopController extends Controller
{
    protected $columns = [
        'type',
        'name',
        'introduction',
        'headimgurl',
        'mobile',
        'telephone',
        'qrcode_url',
        'wechat',
        'weibo',
        'douyin',
        'location_x',
        'location_y',
        'country',
        'province',
        'city',
        'address',
        'start_at',
        'end_at',
        'details',
    ];

    protected $repository;

    public function __construct(ShopRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $this->authorize('view', Shop::class);

        $this->repository->pushCriteria(MyCriteria::class);
        $list = $this->repository->get();

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    public function store(CreateShopRequest $request)
    {
        $this->authorize('create', Shop::class);

        DB::beginTransaction();

        try {
            $shopInfo            = $request->only($this->columns);
            $shopInfo['user_id'] = $this->user->id;
            $shop                = $this->repository->create($shopInfo);

            if ($request->has('projects')) {
                $projects = $request->input('projects');
                $projects = array_fill_keys($projects, 'project_id');
                $projects = data_fill($projects, 'shop_id', $shop->id);

                (new ShopsProjects)->addAll($projects);
            }

            if ($request->has('brands')) {
                $brands = $request->input('brands');
                $brands = array_fill_keys($brands, 'brand_id');
                $brands = data_fill($brands, 'shop_id', $shop->id);

                (new ShopsProjects)->addAll($brands);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return $this->err(FeedBack::CREATE_FAIL);
        }

        DB::commit();

        return $this->suc(['id' => $shop->id]);
    }

    public function show($id)
    {
        $this->authorize('view', Shop::class);

        $shop = $this->repository->with(['brands', 'projects'])->findOrFail($id);

        return $this->suc(compact('shop'));
    }

    public function edit($id)
    {
        //
    }

    public function update(CreateShopRequest $request, $id)
    {
        $this->authorize('view', Shop::class);

        $this->repository->pushCriteria(MyCriteria::class);
        $shop = $this->repository->with(['projects', 'brands'])->findOrFail($id);

        DB::beginTransaction();

        try {
            $shop = $shop->toArray();
            $diff = array_diff_assoc(Arr::only($shop, $this->columns), $request->only($this->columns));
            if ( ! empty($diff)) {
                $this->repository->update($diff, $id);
            }

            if ($request->has('projects')) {
                $oldProjects = array_column($shop['projects'], 'project_id');
                $projects    = $request->input('projects');
                $new         = array_diff($projects, $oldProjects);
                if ( ! empty($new)) {
                    $new = array_fill_keys($new, 'project_id');
                    $new = data_fill($new, 'shop_id', $id);
                    (new ShopsProjects)->addAllWithoutDataTime($new);
                }

                $del = array_diff($oldProjects, $projects);
                if ( ! empty($del)) {
                    ShopsProjects::where('shop_id', $id)->whereIn('project_id', $del)->delete();
                }
            }

            if ($request->has('brands')) {
                $oldBrands = array_column($shop['brands'], 'brand_id');
                $brands    = $request->input('brands');
                $new       = array_diff($brands, $oldBrands);
                if ( ! empty($new)) {
                    $new = array_fill_keys($new, 'brand_id');
                    $new = data_fill($new, 'shop_id', $id);
                    (new ShopsBrands)->addAllWithoutDataTime($new);
                }

                $del = array_diff($oldBrands, $brands);
                if ( ! empty($del)) {
                    ShopsBrands::where('shop_id', $id)->whereIn('brand_id', $del)->delete();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return $this->err();
        }

        DB::commit();

        return $this->suc();
    }

    public function destroy($id)
    {
        $this->authorize('delete', Shop::class);

        $this->repository->pushCriteria(MyCriteria::class);
        $this->repository->delete($id);

        return $this->suc();
    }
}
