<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Material;

use App\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\Material\VideoRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\Material\CreateVideoRequest;

/**
 * 视频素材
 * Class VideoController
 * @package App\Http\Controllers\Api\User\Material
 */
class VideoController extends Controller
{
    protected $videos;

    public function __construct(VideoRepositoryEloquent $videos)
    {
        $this->videos = $videos;
    }

    /**
     * @param VideoRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(VideoRequest $request)
    {
        $this->authorize('view', Videos::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->videos->list($this->user()->id, $request->input('app_id'), $limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateVideoRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateVideoRequest $request)
    {
        $this->authorize('create', Videos::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->videos->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @param VideoRequest $request
     * @param              $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(VideoRequest $request, $id)
    {
        $this->authorize('view', Videos::class);

        $data = $this->videos->get($id, $this->user()->id, $request->input('app_id'));

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param CreateVideoRequest $request
     * @param                    $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateVideoRequest $request, $id)
    {
        $this->authorize('update', Videos::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->videos->update($id, $params);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err($res);
    }

    /**
     * @param VideoRequest $request
     * @param              $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(VideoRequest $request, $id)
    {
        $this->authorize('delete', Videos::class);

        $res = $this->videos->destroy($this->user()->id, $request->input('app_id'), $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }
}
