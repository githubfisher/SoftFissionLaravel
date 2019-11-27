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

    private function getRuleParams(string $appId, array $button, array $oldBtn = [])
    {
        $params = [
            'id'         => empty($oldBtn) ? 0 : $oldBtn['rule']['id'],
            'app_id'     => $appId,
            'title'      => Constant::MENU_RULE_TITLE,
            'scene'      => Constant::REPLY_RULE_SCENE_CLICK,
            'keywords'   => [['keyword' => Constant::MENU_RULE_KEYWORD, 'match_type' => Constant::TRUE_ONE]],
            'replies'    => [[
                'id'                => empty($oldBtn) ? Constant::FLASE_ZERO : $oldBtn['rule']['replies'][Constant::FLASE_ZERO]['id'],
                'difference'        => $button['difference'],
                'reply_type'        => $button['type'],
                'reply_type_female' => $button['type'],
                'content'           => $button['content'],
                'content_female'    => $button['difference'] == Constant::TRUE_ONE ? $button['content_female'] : null,
                'mini_appid'        => $button['type'] == Constant::MENU_TYPE_MINIAPP ? $button['mini_app_id'] : null,
                'pagepath'          => $button['type'] == Constant::MENU_TYPE_MINIAPP ? $button['mini_path'] : null,
            ]],
            'reply_rule' => 1,
        ];

        if (in_array($button['type'], Constant::MENU_NEED_EVENT_TYPES)) {
            $params['keywords'] = [[
                'id'         => empty($oldBtn) ? Constant::FLASE_ZERO : $oldBtn['rule']['keywords'][Constant::FLASE_ZERO]['id'],
                'keyword'    => sprintf(Constant::MENU_EVENT_KEY, $appId, $button['type'], $button['id']),
                'match_type' => Constant::TRUE_ONE,
            ]];
        }

        return $params;
    }

    private function getWeBtnSetting(string $appId, array $button)
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

    public function sortBtns(array $menus)
    {
        foreach ($menus as $k => $menu) {
            $parents = [];
            foreach ($menu['details'] as $key => $button) {
                if ($button['pid'] != 0) {
                    $menus[$k]['details'][$parents[$button['pid']]]['subs'][] = $button;
                    unset($menus[$k]['details'][$key]);
                } else {
                    $parents[$button['id']] = $key;
                }
            }
            $menus[$k]['details'] = array_merge($menus[$k]['details']);
        }

        return $menus;
    }

    private function getEmptyBtn()
    {
        return [
            'difference' => Constant::FLASE_ZERO,
            'type'       => Constant::MENU_TYPE_LINK,
            'content'    => '',
        ];
    }

    private function coverSonPlace(string $appId, int $menuId, int $pid, int $detLen, $list = false)
    {
        $ruleRepository   = app()->make(WeRuleRepositoryEloquent::class);
        $detailRepository = app()->make(WeMenuDetailRepositoryEloquent::class);

        for ($i = $detLen; $i < 5; $i++) {
            $update = $list && isset($list[$i]) ? true : false;
            if ($update) {
                $detailRepository->update(['status'  => Constant::FLASE_ZERO], $list[$i]['id']);
            } else {
                $theDetail = $detailRepository->create([
                    'menu_id' => $menuId,
                    'pid'     => $pid,
                    'rule_id' => Constant::FLASE_ZERO,
                    'name'    => Constant::MENU_SUB_NAME,
                    'status'  => Constant::FLASE_ZERO,
                ]);

                // 回复规则
                $ruleId = $ruleRepository->store($this->getRuleParams($appId, $this->getEmptyBtn()));
                $detailRepository->update(['rule_id' => $ruleId], $theDetail->id);
            }
        }
    }

    private function coverParentPlace($appId, $menuId, $btnLen, $list)
    {
        $ruleRepository   = app()->make(WeRuleRepositoryEloquent::class);
        $detailRepository = app()->make(WeMenuDetailRepositoryEloquent::class);

        for ($i = $btnLen; $i < 3; $i++) {
            $update = $list && isset($list[$i]) ? true : false;
            if ($update) {
                $detailRepository->update(['status'  => Constant::FLASE_ZERO], $list[$i]['id']);

                $this->coverSonPlace($appId, $menuId, $list[$i]['id'], Constant::FLASE_ZERO, $list[$i]['subs']);
            } else {
                $parent = $detailRepository->create([
                    'menu_id' => $menuId,
                    'rule_id' => Constant::FLASE_ZERO,
                    'name'    => Constant::MENU_BTN_NAME,
                    'status'  => Constant::FLASE_ZERO,
                ]);

                // 回复规则
                $ruleId = $ruleRepository->store($this->getRuleParams($appId, $this->getEmptyBtn()));
                $detailRepository->update(['rule_id' => $ruleId], $parent->id);

                $this->coverSonPlace($appId, $menuId, $parent->id, Constant::FLASE_ZERO);
            }
        }
    }

    /**
     * 创建|更新一体方法
     * 自定义菜单|个性化菜单一体方法
     * {"menus":[{"buttons":[{button},{button},{button}],"filter":{}}, {...}]}
     * 每个menu占用3个按钮,每个按钮占用5个子菜单; menu,button,sub均有status标识启用状态
     * 顺序: 自定义菜单, 个性化菜单, 个性化菜单, ...
     * 每次调用接口, 传入所有menus, 一次性创建或更新;
     *
     * @param array $params
     *
     * @return array|bool
     * @throws \Exception
     */
    public function store(array $params)
    {
        // 微信自定义菜单接口参数
        $weBtns = [];

        DB::beginTransaction();

        try {
            // 取旧菜单设置
            $list                   = $this->app($params['appInfo']['app_id'])->with(['details', 'details.rule', 'details.rule.replies'])->get();
            $list                   = $list->toArray();
            ! empty($list) && $list = $this->sortBtns($list);

            $ruleRepository   = app()->make(WeRuleRepositoryEloquent::class);
            $detailRepository = app()->make(WeMenuDetailRepositoryEloquent::class);

            foreach ($params['menus'] as $i =>  $menu) {
                // 创建自定义菜单 // 个性化菜单 | type
                $menuInfo = [
                    'type'   => $i == Constant::FLASE_ZERO ? Constant::TRUE_ONE : $menu['type'],
                    'filter' => $menu['type'] == 2 ? $menu['filter'] : null,
                    'status' => Constant::TRUE_ONE,
                ];

                $update = $list && isset($list[$i]) ? true : false;
                if ($update) {
                    $theMenuId = $list[$i]['id'];
                    $this->update($menuInfo, $theMenuId);
                } else {
                    $menuInfo['app_id'] = $params['appInfo']['app_id'];
                    $theMenu            = $this->create($menuInfo);
                    $theMenuId          = $theMenu->id;
                }

                // 按钮创建|更新
                foreach ($menu['buttons'] as $key => $button) {
                    $weBtns[$key]['name'] = $button['name'];

                    // 父按钮
                    $update = $list && isset($list[$i]['details'][$key]) ? true : false;
                    if ($update) {
                        $pid = $list[$i]['details'][$key]['id'];
                        $detailRepository->update(['name' => $button['name']], $pid);
                        $ruleRepository->updateRule($list[$i]['details'][$key]['rule']['id'], $this->getRuleParams($params['appInfo']['app_id'], $button, $list[$i]['details'][$key]));
                    } else {
                        $parent = $detailRepository->create([
                            'menu_id' => $theMenuId,
                            'name'    => $button['name'],
                            'rule_id' => Constant::FLASE_ZERO,
                        ]);
                        $button['id'] = $pid = $parent->id;

                        // 回复规则
                        $ruleId = $ruleRepository->store($this->getRuleParams($params['appInfo']['app_id'], $button));
                        $detailRepository->update(['rule_id' => $ruleId], $parent->id);
                    }

                    // 无子按钮
                    if ( ! isset($button['subs']) || empty($button['subs'])) {
                        // 微信菜单配置项
                        $weBtnSetting = $this->getWeBtnSetting($params['appInfo']['app_id'], $button);
                        $weBtns[$key] = array_merge($weBtns[$key], $weBtnSetting);

                        // 子按钮占位
                        $this->coverSonPlace($params['appInfo']['app_id'], $theMenuId, $pid, Constant::FLASE_ZERO);
                    } else {
                        // 子按钮
                        foreach ($button['subs'] as $k =>  $sub) {
                            $update = $list && isset($list[$i]['details'][$key]['subs'][$k]) ? true : false;
                            if ($update) {
                                $theDetailId = $list[$i]['details'][$key]['subs'][$k]['id'];
                                $detailRepository->update(['name' => $button['name']], $theDetailId);
                                $ruleRepository->updateRule($list[$i]['details'][$key]['subs'][$k]['rule']['id'], $this->getRuleParams($params['appInfo']['app_id'], $sub, $list[$i]['details'][$key]['subs'][$k]));
                            } else {
                                $theDetail = $detailRepository->create([
                                    'menu_id' => $theMenuId,
                                    'pid'     => $pid,
                                    'rule_id' => Constant::FLASE_ZERO,
                                    'name'    => $sub['name'],
                                ]);
                                $sub['id'] = $theDetailId = $theDetail->id;

                                // 回复规则
                                $ruleId = $ruleRepository->store($this->getRuleParams($params['appInfo']['app_id'], $sub));
                                $detailRepository->update(['rule_id' => $ruleId], $theDetailId);
                            }

                            // 微信菜单配置项
                            $weBtns[$key]['sub_button'][$k]         = $this->getWeBtnSetting($params['appInfo']['app_id'], $sub);
                            $weBtns[$key]['sub_button'][$k]['name'] = $sub['name'];
                        }

                        // 子按钮占位
                        $this->coverSonPlace($params['appInfo']['app_id'], $theMenuId, $pid, count($button['subs']), $list[$i]['details'][$key]['subs']);
                    }
                }

                // 父按钮占位
                $this->coverParentPlace($params['appInfo']['app_id'], $theMenuId, count($menu['buttons']), $list[$i]['details']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return false;
        }

        DB::commit();

        return $weBtns;
    }
}
