<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\AutoReply;

use App\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Reply\Rule;
use App\Models\User\Reply\Rule as Rules;
use App\Http\Requests\User\OpenPlatform\AutoReply\RuleRequest;
use App\Http\Requests\User\OpenPlatform\AutoReply\CreateRuleRequest;

class SubscribeController extends Controller
{
    protected $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function store(CreateRuleRequest $request)
    {
        $this->authorize('create', Rules::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $params['scene']   = Constant::REPLY_RULE_SCENE_SUBSCRIBE;
        $res               = $this->rule->storeSubscribeRule($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    public function show(RuleRequest $request)
    {
        $this->authorize('view', Rules::class);

        $data = $this->rule->getSubscribeRule($this->user()->id, $request->input('app_id'));

        return $this->suc(compact('data'));
    }
}
