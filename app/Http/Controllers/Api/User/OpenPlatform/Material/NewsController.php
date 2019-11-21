<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Material;

use DB;
use Log;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Criteria\MyCriteria;
use App\Http\Controllers\Controller;
use App\Models\User\Material\News as Material;
use App\Repositories\Material\NewsRepositoryEloquent;
use App\Repositories\Material\NewsDetailRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\Material\NewsRequest;
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

    public function index(NewsRequest $request)
    {
        $this->authorize('view', Material::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $this->repository->pushCriteria(MyCriteria::class);
        $list = $this->repository->with(['details'])->scopeQuery(function ($query) use ($request) {
            return $query->where('app_id', $request->input('app_id'));
        })->paginate($limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    public function store(CreateNewsRequest $request, NewsDetailRepositoryEloquent $repository)
    {
        $this->authorize('create', Material::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;

        DB::beginTransaction();

        try {
            $data = [
                'user_id' => $params['user_id'],
                'app_id'  => $params['app_id'],
            ];
            $news = $this->repository->create($data);

            foreach ($params['details'] as $key => $detail) {
                $detail['news_id'] = $news->id;
                $detail['sort']    = $key;
                $repository->create($detail);
            }

            // 关联海报, 图片 TODO
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return $this->err(FeedBack::CREATE_FAIL);
        }

        DB::commit();

        return $this->suc(['id' => $news->id]);
    }

    public function show(NewsRequest $request, $id)
    {
        $this->authorize('view', Material::class);

        $this->repository->pushCriteria(MyCriteria::class);
        $data = $this->repository->app($request->input('app_id'))->with('details')->findOrFail($id);

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
