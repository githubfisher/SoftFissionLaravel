<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\AutoReply;

use App\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Reply\Rule;
use App\Models\User\Reply\Rule as Rules;
use App\Http\Requests\User\OpenPlatform\AutoReply\WeRuleRequest;
use App\Http\Requests\User\OpenPlatform\AutoReply\CreateWeRuleRequest;

class AnyController extends Controller
{
    protected $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function store(CreateWeRuleRequest $request)
    {
        $this->authorize('create', Rules::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $params['scene']   = Constant::REPLY_RULE_SCENE_ANY;
        $res               = $this->rule->storeAnyRule($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    public function show(WeRuleRequest $request)
    {
        $this->authorize('view', Rules::class);

        $data = $this->rule->getAnyRule($this->user()->id, $request->input('app_id'));

        return $this->suc(compact('data'));
    }
}
