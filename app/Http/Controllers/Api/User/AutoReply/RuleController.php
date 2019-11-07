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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(RuleRequest $request)
    {
        $this->authorize('view', Rules::class);

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
        $params['scene']   = Constant::REPLY_RULE_SCENE_KEYWORD;
        $res               = $this->rule->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @param       $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', Rules::class);

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
     * @param CreateRuleRequest $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateRuleRequest $request, $id)
    {
        $this->authorize('update', Rules::class);

        if ($this->rule->update($id, $request->all())) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RuleRequest $request
     * @param             $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(RuleRequest $request, $id)
    {
        $this->authorize('delete', Rules::class);

        if ($this->rule->destroy($this->user()->id, $request->input('app_id'), $id)) {
            return $this->suc();
        }

        return $this->err();
    }
}
