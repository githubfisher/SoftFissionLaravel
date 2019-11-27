<?php
namespace App\Repositories\Material;

use DB;
use Log;
use App\Utilities\FeedBack;
use Illuminate\Support\Arr;
use App\Entities\Material\WeNews;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class NewsRepositoryEloquent.
 *
 * @package namespace App\Repositories\Material;
 */
class NewsRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WeNews::class;
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
        return 'App\\Validators\\Material\\NewsValidator';
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function store(array $params)
    {
        DB::beginTransaction();

        try {
            $data = [
                'app_id' => $params['app_id'],
            ];
            $news = $this->create($data);

            $repository = app()->make(NewsDetailRepositoryEloquent::class);
            foreach ($params['details'] as $key => $detail) {
                $detail['news_id'] = $news->id;
                $detail['sort']    = $key;
                $repository->create($detail);
            }

            // 关联海报, 图片 TODO
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return $this->err(FeedBack::CREATE_FAIL);
        }

        DB::commit();

        return $news->id;
    }

    /**
     * @param array $params
     * @param int   $id
     *
     * @return array|bool
     */
    public function updateNews(array $params, int $id)
    {
        DB::beginTransaction();

        try {
            $news = $this->app($params['app_id'])->with('details')->find($id);
            if ($news) {
                $updated = false;
                $still   = [];
                $news    = $news->toArray();
                $details = array_column($news['details'], null, 'id');
                $col     = ['title', 'author', 'digest', 'thumb_url', 'content', 'content_source_url', 'poster_id', 'image_id', 'sort'];

                $repository = app()->make(NewsDetailRepositoryEloquent::class);
                foreach ($params['details'] as $key => $detail) {
                    $detail['sort'] = $key;
                    if (isset($detail['id']) && $detail['id']) {
                        $data = Arr::only($detail, $col);
                        $diff = array_diff_assoc($data, Arr::only($details[$detail['id']], array_keys($data)));
                        if ( ! empty($diff)) {
                            $repository->update($diff, $detail['id']);
                            $updated = true;
                        }
                        $still[] = $detail['id'];
                    } else {
                        $detail['news_id'] = $id;
                        $repository->create($detail);
                        $updated = true;
                    }
                }

                // 清理旧文章 // Todo 引用保护
                $del = array_diff(array_keys($details), $still);
                count($del) && $repository->deleteWhereIn('id', $del);

                // 清理关联海报, 图片关系 TODO

                if ($updated) {
                    $this->update(['app_id' => $params['app_id']], $news['id']);
                }
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . ' ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            DB::rollBack();

            return FeedBack::UPDATE_FAIL;
        }

        DB::commit();

        return true;
    }

    /**
     * @param int    $id
     * @param string $appId
     *
     * @return bool|int
     */
    public function destory(int $id, string $appId)
    {
        // 引用保护 TODO

        $news = $this->app($appId)->findOrFail($id);
        if ($news) {
            if (empty($news->media_id)) {
                return $this->delete($id);
            }
        }

        return false;
    }
}
