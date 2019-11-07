<?php
namespace App\Http\Controllers\Api\User\AutoReply;

use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Reply\Rule;
use App\Http\Requests\User\AutoReply\RuleRequest;
use App\Http\Requests\User\AutoReply\CreateRuleRequest;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param RuleRequest $request
     * @param Rule        $rule
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RuleRequest $request, Rule $rule)
    {
        $list = $rule->list($this->user()->id, $request->input('app_id'), Constant::REPLY_RULE_SCENE_KEYWORD);

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
     * Store a newly created resource in storage.
     *
     * @param CreateRuleRequest $request
     * @param Rule              $rule
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRuleRequest $request, Rule $rule)
    {
        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $rule->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @param Rule $rule
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Rule $rule)
    {
        $data = $rule->get($id);

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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param         $id
     * @param Rule $rule
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, Rule $rule)
    {
        if ($rule->update($id, $request->all())) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param         $id
     * @param Rule $rule
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Rule $rule)
    {
        if ($rule->destroy($id)) {
            return $this->suc();
        }

        return $this->err();
    }
}
