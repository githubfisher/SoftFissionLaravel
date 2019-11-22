<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\AutoReply;

use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\Reply\WeRule;
use App\Http\Controllers\Controller;
use App\Repositories\Reply\WeRuleRepositoryEloquent;
use App\Http\Requests\User\OpenPlatform\AutoReply\CreateWeRuleRequest;

/**
 * 任意回复
 *
 * Class AnyController
 * @package App\Http\Controllers\Api\User\OpenPlatform\AutoReply
 */
class AnyController extends Controller
{
    protected $repository;

    public function __construct(WeRuleRepositoryEloquent $repository)
    {
        $this->repository = $repository;
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
        $params['scene']   = Constant::REPLY_RULE_SCENE_ANY;
        $res               = $this->repository->storeAnyRule($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show()
    {
        $this->authorize('view', WeRule::class);

        if ($id = $this->repository->getIdByScene(current_weapp()['app_id'])) {
            $data = $this->repository->find($id);

            return $this->suc(compact('data'));
        }

        return $this->err(FeedBack::RULE_NOT_FOUND);
    }
}
