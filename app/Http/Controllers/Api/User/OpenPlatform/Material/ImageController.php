<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Material;

use App\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Material\Image;
use App\Models\User\Material\Image as Images;
use App\Http\Requests\User\Material\ImagesRequest;
use App\Http\Requests\User\Material\CreateImagesRequest;

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
     * @param ImagesRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(ImagesRequest $request)
    {
        $this->authorize('view', Images::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->images->list($this->user()->id, $request->input('app_id'), $limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateImagesRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateImagesRequest $request)
    {
        $this->authorize('create', Images::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->images->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @param ImagesRequest $request
     * @param               $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(ImagesRequest $request, $id)
    {
        $this->authorize('view', Images::class);

        $data = $this->images->get($id, $this->user()->id, $request->input('app_id'));

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param CreateImagesRequest $request
     * @param                     $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateImagesRequest $request, $id)
    {
        $this->authorize('update', Images::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->images->update($id, $params);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err($res);
    }

    /**
     * @param ImagesRequest $request
     * @param               $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(ImagesRequest $request, $id)
    {
        $this->authorize('delete', Images::class);

        $res = $this->images->destroy($this->user()->id, $request->input('app_id'), $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }
}
