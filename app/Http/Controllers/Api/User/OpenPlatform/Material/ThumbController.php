<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Material;

use App\Utilities\Constant;
use App\Entities\Material\WeThumb;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Repositories\Material\ThumbRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\Material\CreateThumbsRequest;

/**
 * 缩略图素材
 * Class ThumbController
 * @package App\Http\Controllers\Api\User\Material
 */
class ThumbController extends Controller
{
    protected $thumbs;

    public function __construct(ThumbRepositoryEloquent $thumbs)
    {
        $this->thumbs = $thumbs;
    }

    /**
     * @param PaginateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(PaginateRequest $request)
    {
        $this->authorize('view', WeThumb::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->thumbs->list($this->user()->id, $request->input('app_id'), $limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateThumbsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateThumbsRequest $request)
    {
        $this->authorize('create', Thumbs::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->thumbs->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @param             $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', Thumbs::class);

        $data = $this->thumbs->get($id, $this->user()->id, $request->input('app_id'));

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param CreateThumbsRequest $request
     * @param                   $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateThumbsRequest $request, $id)
    {
        $this->authorize('update', Thumbs::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->thumbs->update($id, $params);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err($res);
    }

    /**
     * @param             $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('delete', Thumbs::class);

        $res = $this->thumbs->destroy($this->user()->id, $request->input('app_id'), $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }
}
