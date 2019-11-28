<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Material;

use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\Material\WeImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Repositories\Material\ImageRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\Material\CreateImagesRequest;

/**
 * 图片素材
 * Class ImageController
 * @package App\Http\Controllers\Api\User\Material
 */
class ImageController extends Controller
{
    protected $repository;

    public function __construct(ImageRepositoryEloquent $repository)
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
        $this->authorize('view', WeImage::class);

        $list  = $this->repository->app(current_weapp()['app_id'])->paginate($request->input('limit', Constant::PAGINATE_MIN));

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
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateImagesRequest $request)
    {
        $this->authorize('create', WeImage::class);

        $params           = $request->all();
        $params['app_id'] = current_weapp()['app_id'];
        $image            = $this->repository->create($params);
        if ($image) {
            return $this->suc(['id' => $image->id]);
        }

        return $this->err(FeedBack::CREATE_FAIL);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', WeImage::class);

        $image = $this->repository->app(current_weapp()['app_id'])->findOrFail($id);

        return $this->suc(compact('image'));
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
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CreateImagesRequest $request, $id)
    {
        $this->authorize('update', WeImage::class);

        $params = $request->all();
        $image  = $this->repository->update($params, $id);
        if ($image) {
            return $this->suc();
        }

        return $this->err(FeedBack::UPDATE_FAIL);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('delete', WeImage::class);

        $image = $this->repository->app(current_weapp()['app_id'])->findOrFail($id);
        if ($image) {
            if ($res = $this->repository->delete($id)) {
                return $this->suc();
            }
        }

        return $this->err(FeedBack::DELETE_FAIL);
    }
}
