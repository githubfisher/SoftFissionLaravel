<?php
namespace App\Http\Controllers\Api\User\Material;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Material\Image;

/**
 * 图片素材
 * Class ImageController
 * @package App\Http\Controllers\Api\User\Material
 */
class ImageController extends Controller
{
    protected $images;

    public function __construct(Image $images)
    {
        $this->images = $images;
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
        $list  = $this->news->list($this->user()->id, $request->input('app_id'), Constant::REPLY_RULE_SCENE_KEYWORD, $limit);

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
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', Material::class);

        $data = $this->news->get($id);

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

        if ($this->news->destroy($this->user()->id, $request->input('app_id'), $id)) {
            return $this->suc();
        }

        return $this->err();
    }
}
