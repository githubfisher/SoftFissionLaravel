<?php
namespace App\Http\Controllers\Api\User\Material;

use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Material\Thumb;
use App\Models\User\Material\Thumb as Thumbs;
use App\Http\Requests\User\Material\ThumbsRequest;
use App\Http\Requests\User\Material\CreateThumbsRequest;

/**
 * 缩略图素材
 * Class ThumbController
 * @package App\Http\Controllers\Api\User\Material
 */
class ThumbController extends Controller
{
    protected $thumbs;

    public function __construct(Thumb $thumbs)
    {
        $this->thumbs = $thumbs;
    }

    /**
     * @param ThumbsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(ThumbsRequest $request)
    {
        $this->authorize('view', Thumbs::class);

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
     * @param ThumbsRequest $request
     * @param             $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(ThumbsRequest $request, $id)
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
     * @param ThumbsRequest $request
     * @param             $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(ThumbsRequest $request, $id)
    {
        $this->authorize('delete', Thumbs::class);

        $res = $this->thumbs->destroy($this->user()->id, $request->input('app_id'), $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }
}
