<?php
namespace App\Http\Controllers\Api\User\AutoReply;

use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Reply\Rule;
use App\Models\User\Reply\Rule as Rules;
use App\Http\Requests\User\AutoReply\RuleRequest;
use App\Http\Requests\User\AutoReply\CreateRuleRequest;

class RuleController extends Controller
{
    protected $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }
    /**
     * Display a listing of the resource.
     *
     * @param RuleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RuleRequest $request)
    {
        $list = $this->rule->list($this->user()->id, $request->input('app_id'), Constant::REPLY_RULE_SCENE_KEYWORD);

        return $this->suc(compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @param CreateRuleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateRuleRequest $request)
    {
        $this->authorize('create', Rules::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $this->rule->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @param       $id
     * @param Rules $rule
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id, Rules $rule)
    {
        $this->authorize('view', $rule);

        $data = $this->rule->get($id);

        return $this->suc(compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param         $id
     * @param Rules   $rule
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id, Rules $rule)
    {
        $this->authorize('update', $rule);

        if ($this->rule->update($id, $request->all())) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param       $id
     * @param Rules $rule
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id, Rules $rule)
    {
        $this->authorize('delete', $rule);

        if ($this->rule->destroy($id)) {
            return $this->suc();
        }

        return $this->err();
    }
}
