<?php
namespace App\Repositories\Menu;

use DB;
use Log;
use App\Utilities\Constant;
use App\Entities\Menu\WeMenu;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Traits\CacheableRepository;
use App\Repositories\Reply\WeRuleRepositoryEloquent;
use Prettus\Repository\Contracts\CacheableInterface;

/**
 * Class MenuRepositoryEloquent.
 *
 * @package namespace App\Repositories\Menu;
 */
class WeMenuRepositoryEloquent extends BaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WeMenu::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {
        return 'App\\Validators\\Menu\\MenuValidator';
    }

    private function getRuleParams($appId, $button)
    {
        $params = [
            'app_id'     => $appId,
            'title'      => '自定义菜单',
            'scene'      => Constant::REPLY_RULE_SCENE_CLICK,
            'keywords'   => [],
            'replies'    => [
                'difference'         => $button['difference'],
                'reply_type'         => $button['type'],
                'reply_type_female'  => $button['type'],
                'content'            => $button['content'],
                'content_female'     => $button['difference'] == Constant::TRUE_ONE ? $button['content_female'] : null,
                'mini_app_id'        => $button['type'] == Constant::MENU_TYPE_MINIAPP ? $button['mini_app_id'] : null,
                'mini_app_id_female' => $button['type'] == Constant::MENU_TYPE_MINIAPP && $button['difference'] == Constant::TRUE_ONE ? $button['mini_app_id_female'] : null,
                'mini_path'          => $button['type'] == Constant::MENU_TYPE_MINIAPP ? $button['mini_path'] : null,
                'mini_path_female'   => $button['type'] == Constant::MENU_TYPE_MINIAPP && $button['difference'] == Constant::TRUE_ONE ? $button['mini_path_female'] : null,
            ],
            'reply_rule' => 1,
        ];

        if (in_array($button['type'], Constant::MENU_NEED_EVENT_TYPES)) {
            $params['keywords'] = [
                'keyword'    => sprintf(Constant::MENU_EVENT_KEY, $appId, $button['type'], $button['id']),
                'match_type' => Constant::TRUE_ONE,
            ];
        }

        return $params;
    }

    private function getWeBtnSetting($appId, $button)
    {
        $setting = [];
        $type    = Constant::WECHAT_MSG_TYPE[$button['type']];
        if ($type == 'view') {
            $setting['url'] = $button['content'];
        } elseif ($type == 'click_event') { // 自定义EventKey
            $setting['type'] = 'click';
            $setting['key']  = $button['content'];
        } elseif ($type == 'miniprogram') {
            $setting['type']     = 'miniprogram';
            $setting['appid']    = $button['miniappid'];
            $setting['pagepath'] = $button['pagepath'];
            $setting['url']      = $button['content'];
        } else {
            $setting['type'] = 'click';
            $setting['key']  = sprintf(Constant::MENU_EVENT_KEY, $appId, $button['type'], $button['id']);
        }

        return $setting;
    }

    public function store(array $params)
    {
        // 微信自定义菜单接口参数
        $weBtns = [];

        DB::beginTransaction();

        try {
            foreach ($params['menus'] as $menu) {
                $ruleRepository   = app()->make(WeRuleRepositoryEloquent::class);
                $detailRepository = app()->make(WeMenuDetailRepositoryEloquent::class);

                // 创建自定义菜单 // 个性化菜单 | type
                $theMenu = $this->create([
                    'app_id' => $params['appInfo']['app_id'],
                    'type'   => $menu['type'],
                    'filter' => $menu['type'] == 2 ? $menu['filter'] : null,
                ]);

                // 创建菜单
                foreach ($menu['buttons'] as $key => $button) {
                    $weBtns[$key]['name'] = $button['name'];

                    // 无子菜单
                    if (empty($button['subs'])) {
                        $theDetail = $detailRepository->create([
                            'menu_id' => $theMenu->id,
                            'rule_id' => Constant::FLASE_ZERO,
                            'name'    => $button['name'],
                        ]);
                        $button['id'] = $theDetail->id;

                        // 微信设置
                        $weBtnSetting = $this->getWeBtnSetting($params['appInfo']['app_id'], $button);
                        $weBtns[$key] = array_merge($weBtns[$key], $weBtnSetting);

                        if (in_array($button['type'], Constant::MENU_NEED_EVENT_TYPES)) {
                            $ruleId = $ruleRepository->store($this->getRuleParams($params['appInfo']['app_id'], $button));
                            $detailRepository->update(['rule_id' => $ruleId], $theDetail->id);
                        }
                    } else {
                        // 主菜单
                        $theBtn = $detailRepository->create([
                            'menu_id' => $theMenu->id,
                            'name'    => $button['name'],
                        ]);

                        // 子菜单
                        foreach ($button['subs'] as $k =>  $sub) {
                            $theDetail = $detailRepository->create([
                                'menu_id' => $theMenu->id,
                                'pid'     => $theBtn->id,
                                'rule_id' => Constant::FLASE_ZERO,
                                'name'    => $sub['name'],
                            ]);
                            $sub['id'] = $theDetail->id;

                            // 微信设置
                            $weBtns[$key]['sub_button'][$k]         = $this->getWeBtnSetting($params['appInfo']['app_id'], $sub);
                            $weBtns[$key]['sub_button'][$k]['name'] = $sub['name'];

                            if (in_array($sub['type'], Constant::MENU_NEED_EVENT_TYPES)) {
                                $ruleId = $ruleRepository->store($this->getRuleParams($params['appInfo']['app_id'], $sub));
                                $detailRepository->update(['rule_id' => $ruleId], $theDetail->id);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return false;
        }

        DB::commit();

        return $weBtns;
    }

    public function updateMenu(int $id, array $params)
    {
    }
}
