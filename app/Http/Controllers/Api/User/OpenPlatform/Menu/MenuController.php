<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Menu;

use Log;
use EasyWeChat\Factory;
use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\Menu\WeMenu;
use App\Http\Controllers\Controller;
use App\Repositories\Menu\WeMenuRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\Menu\CreateMenuRequest;

class MenuController extends Controller
{
    protected $repository;

    public function __construct(WeMenuRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', WeMenu::class);

        $list                   = $this->repository->app(current_weapp()['app_id'])->with(['details', 'details.rule', 'details.rule.replies'])->get();
        $list                   = $list->toArray();
        ! empty($list) && $list = $this->repository->sortBtns($list);

        return $this->suc($list);
    }

    /**
     * @param CreateMenuRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateMenuRequest $request)
    {
        $this->authorize('create', WeMenu::class);

        $params            = $request->all();
        $params['appInfo'] = current_weapp();
        $weBtns            = $this->repository->store($params);
        if (is_array($weBtns)) {
            Log::debug(__FUNCTION__ . ' we_btn_setting: ' . json_encode($weBtns));

            try {
                $officialAccount = Factory::openPlatform(config('wechat.open_platform.default'))->officialAccount($params['appInfo']['app_id'], $params['appInfo']['refresh_token']);
                //$res             = $officialAccount->menu->create($weBtns);
                $res['errcode'] = 0;
                if ($res['errcode'] == Constant::FLASE_ZERO) {
                    return $this->suc();
                }

                Log::error(__FUNCTION__ . ' ' . $res['errmsg']);
            } catch (\Exception $e) {
                Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

                return $this->err(FeedBack::CREATE_FAIL);
            }
        }
        Log::error(__FUNCTION__ . ' wrong we_btn_setting form ');

        return $this->err(FeedBack::CREATE_FAIL);
    }

    /**
     * @param CreateMenuRequest $request
     * @param                   $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateMenuRequest $request, $id)
    {
        $this->authorize('update', WeMenu::class);

        if ($this->repository->updateMenu($id, $request->all())) {
            return $this->suc();
        }

        return $this->err();
    }
}
