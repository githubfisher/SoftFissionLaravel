<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\AutoReply;

use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\Reply\WeRule;
use App\Http\Controllers\Controller;
use App\Repositories\Reply\WeRuleRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\AutoReply\WeRuleRequest;
use App\Http\Requests\User\OpenPlatform\AutoReply\CreateWeRuleRequest;

class RuleController extends Controller
{
    protected $repository;

    public function __construct(WeRuleRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param WeRuleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(WeRuleRequest $request)
    {
        $this->authorize('view', WeRule::class);

        $list  = $this->repository->app(current_weapp()['app_id'])
            ->scene(Constant::REPLY_RULE_SCENE_KEYWORD)
            ->simplePaginate($request->input('limit', Constant::PAGINATE_MIN));

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
     * @param CreateWeRuleRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateWeRuleRequest $request)
    {
        $this->authorize('create', WeRule::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $params['scene']   = Constant::REPLY_RULE_SCENE_KEYWORD;
        $res               = $this->repository->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err(FeedBack::CREATE_FAIL);
    }

    /**
     * @param       $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', WeRule::class);

        $data = $this->repository->get($id);

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
     * @param CreateWeRuleRequest $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreateWeRuleRequest $request, $id)
    {
        $this->authorize('update', WeRule::class);

        if ($this->repository->update($id, $request->all())) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param WeRuleRequest $request
     * @param             $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(WeRuleRequest $request, $id)
    {
        $this->authorize('delete', WeRule::class);

        if ($this->repository->destroy($this->user()->id, $request->input('app_id'), $id, Constant::REPLY_RULE_SCENE_KEYWORD)) {
            return $this->suc();
        }

        return $this->err();
    }
}
