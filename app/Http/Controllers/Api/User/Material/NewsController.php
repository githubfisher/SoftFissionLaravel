<?php
namespace App\Http\Controllers\Api\User\Material;

use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Material\News;
use App\Models\User\Material\News as Material;
use App\Http\Requests\User\Material\NewsRequest;
use App\Http\Requests\User\Material\CreateNewsRequest;

/**
 * 图文素材
 * Class NewsController
 * @package App\Http\Controllers\Api\User\Material
 */
class NewsController extends Controller
{
    protected $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * @param NewsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(NewsRequest $request)
    {
        $this->authorize('view', Material::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->news->list($this->user()->id, $request->input('app_id'), $limit);

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
        $res               = $this->news->store($params);
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

        $data = $this->news->get($id, $this->user()->id, $request->input('app_id'));

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

        if ($this->news->update($id, $request->all())) {
            return $this->suc();
        }

        return $this->err();
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

        if ($this->news->destory($id, $this->user()->id, $request->input('app_id'))) {
            return $this->suc();
        }

        return $this->err();
    }
}
