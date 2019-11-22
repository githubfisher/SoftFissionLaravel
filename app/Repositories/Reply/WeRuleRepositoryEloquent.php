<?php
namespace App\Repositories\Reply;

use DB;
use Log;
use App\Utilities\Constant;
use App\Entities\Reply\WeRule;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Contracts\CacheableInterface;

/**
 * Class RuleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Reply;
 */
class WeRuleRepositoryEloquent extends BaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WeRule::class;
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
        return 'App\\Validators\\Reply\\WeRuleValidator';
    }

    public function store($params)
    {
        $keywords = isset($params['keywords']) ? $params['keywords'] : [];
        $replies  = $params['replies'];

        DB::beginTransaction();

        try {
            $rule = $this->create([
                'app_id'     => $params['app_id'],
                'scene'      => $params['scene'],
                'title'      => $params['title'],
                'reply_rule' => $params['reply_rule'],
                'start_at'   => isset($params['start_at']) ? $params['start_at'] : null,
                'end_at'     => isset($params['end_at']) ? $params['end_at'] : null,
                'status'     => isset($params['status']) ? $params['status'] : Constant::FLASE_ZERO,
            ]);

            if (count($keywords)) {
                data_fill($keywords, '*.rule_id', $rule->id);
                app()->make(WeKeywordRepositoryEloquent::class)->addAll($keywords);
            }

            data_fill($replies, '*.rule_id', $rule->id);
            app()->make(WeReplyRepositoryEloquent::class)->addAll($replies);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return false;
        }

        DB::commit();

        return $rule->id;
    }
}
