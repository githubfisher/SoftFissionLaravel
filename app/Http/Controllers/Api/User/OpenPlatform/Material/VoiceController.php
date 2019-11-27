<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Material;

use App\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\Material\VoiceRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\Material\CreateVoiceRequest;

/**
 * 音频素材
 * Class VoiceController
 * @package App\Http\Controllers\Api\User\Material
 */
class VoiceController extends Controller
{
    protected $voice;

    public function __construct(VoiceRepositoryEloquent $voice)
    {
        $this->voice = $voice;
    }

    /**
     * @param VoiceRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(VoiceRequest $request)
    {
        $this->authorize('view', Voices::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->voice->list($this->user()->id, $request->input('app_id'), $limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateVoiceRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateVoiceRequest $request)
    {
        $this->authorize('create', Voices::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->voice->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @param VoiceRequest $request
     * @param              $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(VoiceRequest $request, $id)
    {
        $this->authorize('view', Voices::class);

        $data = $this->voice->get($id, $this->user()->id, $request->input('app_id'));

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param CreateVoiceRequest $request
     * @param                    $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateVoiceRequest $request, $id)
    {
        $this->authorize('update', Voices::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->voice->update($id, $params);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err($res);
    }

    /**
     * @param VoiceRequest $request
     * @param              $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(VoiceRequest $request, $id)
    {
        $this->authorize('delete', Voices::class);

        $res = $this->voice->destroy($this->user()->id, $request->input('app_id'), $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }
}
