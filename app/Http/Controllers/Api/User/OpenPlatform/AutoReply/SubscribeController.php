<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\AutoReply;

use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\Reply\WeRule;
use App\Http\Controllers\Controller;
use App\Repositories\Reply\WeRuleRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\AutoReply\WeRuleRequest;
use App\Http\Requests\User\OpenPlatform\AutoReply\CreateWeRuleRequest;

class SubscribeController extends Controller
{
    protected $repository;

    public function __construct(WeRuleRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function store(CreateWeRuleRequest $request)
    {
        $this->authorize('create', WeRule::class);

        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $params['scene']   = Constant::REPLY_RULE_SCENE_SUBSCRIBE;
        $res               = $this->repository->storeSubscribeRule($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    public function show(WeRuleRequest $request)
    {
        $this->authorize('view', WeRule::class);

        if ($id = $this->repository->getIdByScene(current_weapp()['app_id'], Constant::REPLY_RULE_SCENE_SUBSCRIBE)) {
            $data = $this->repository->find($id);

            return $this->suc(compact('data'));
        }

        return $this->err(FeedBack::RULE_NOT_FOUND);
    }
}
