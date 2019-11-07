<?php
namespace App\Http\Controllers\Api\User\AutoReply;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Reply\Rule;
use App\Models\User\Reply\Rule as Rules;
use App\Http\Requests\User\AutoReply\CreateRuleRequest;

class AnyController extends Controller
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
        $res               = $this->rule->storeAnyRule($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    public function show()
    {
        $this->authorize('view', Rules::class);

        $data = $this->rule->getAnyRule();

        return $this->suc(compact('data'));
    }

    public function update(CreateRuleRequest $request)
    {
        $this->authorize('update', Rules::class);

        if ($this->rule->updateAnyRule($request->all())) {
            return $this->suc();
        }

        return $this->err();
    }
}
