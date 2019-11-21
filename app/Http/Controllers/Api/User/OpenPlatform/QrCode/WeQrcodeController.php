<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\QrCode;

use App\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Models\User\SuperQrCode\QrCode;
use App\Repositories\QrCode\WeQrcodeRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\QrCode\WeQrcodeRequest;
use App\Http\Requests\User\OpenPlatform\QrCode\CreateWeQrcodeRequest;
use App\Http\Requests\User\OpenPlatform\QrCode\UpdateWeQrcodeRequest;

/**
 * 超级二维码
 * Class QrCodeController
 * @package App\Http\Controllers\Api\User\SuperQrCode
 */
class WeQrcodeController extends Controller
{
    protected $repository;

    public function __construct(WeQrcodeRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param WeQrcodeRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(WeQrcodeRequest $request)
    {
        $this->authorize('view', QrCode::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->repository->local($this->user()->id)->app($request->input('app_id'))->recent()->simplePaginate($limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateWeQrcodeRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateWeQrcodeRequest $request)
    {
        $this->authorize('create', QrCode::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $params['scene']   = Constant::REPLY_RULE_SCENE_SCAN;
        $res               = $this->repository->store($params);
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
        $this->authorize('view', QrCode::class);

        $data = $this->repository->get($id);

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param UpdateWeQrcodeRequest $request
     * @param                     $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateWeQrcodeRequest $request, $id)
    {
        $this->authorize('update', QrCode::class);

        $res = $this->repository->update($id, $request->all());
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * @param WeQrcodeRequest $request
     * @param               $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(WeQrcodeRequest $request, $id)
    {
        $this->authorize('delete', QrCode::class);

        $res = $this->repository->destroy($this->user()->id, $request->input('app_id'), $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }
}
