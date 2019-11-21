<?php
namespace App\Http\Controllers\Api\User\SuperQrCode;

use App\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Models\User\SuperQrCode\QrCode;
use App\Http\Requests\User\SuperQrCode\QrCodeRequest;
use App\Http\Repositories\SuperQrCode\QrCode as QrCodes;
use App\Http\Requests\User\SuperQrCode\CreateQrCodeRequest;
use App\Http\Requests\User\SuperQrCode\UpdateQrCodeRequest;

/**
 * 超级二维码
 * Class QrCodeController
 * @package App\Http\Controllers\Api\User\SuperQrCode
 */
class WeQrcodeController extends Controller
{
    protected $qrcode;

    public function __construct(QrCodes $qrCode)
    {
        $this->qrcode = $qrCode;
    }

    /**
     * @param QrCodeRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(QrCodeRequest $request)
    {
        $this->authorize('view', QrCode::class);

        $limit = $request->input('limit', Constant::PAGINATE_MIN);
        $list  = $this->qrcode->list($this->user()->id, $request->input('app_id'), $limit);

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    /**
     * @param CreateQrCodeRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateQrCodeRequest $request)
    {
        $this->authorize('create', QrCode::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $params['scene']   = Constant::REPLY_RULE_SCENE_SCAN;
        $res               = $this->qrcode->store($params);
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

        $data = $this->qrcode->get($id);

        return $this->suc(compact('data'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * @param UpdateQrCodeRequest $request
     * @param                     $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateQrCodeRequest $request, $id)
    {
        $this->authorize('update', QrCode::class);

        $res = $this->qrcode->update($id, $request->all());
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * @param QrCodeRequest $request
     * @param               $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(QrCodeRequest $request, $id)
    {
        $this->authorize('delete', QrCode::class);

        $res = $this->qrcode->destroy($this->user()->id, $request->input('app_id'), $id);
        if ($res === true) {
            return $this->suc();
        }

        return $this->err();
    }
}
