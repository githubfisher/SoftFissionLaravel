<?php
namespace App\Http\Controllers\Api\User\AutoReply;

use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Reply\Keyword;
use App\Http\Requests\User\AutoReply\KeywordRequest;
use App\Http\Requests\User\AutoReply\CreateKeywordRequest;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param KeywordRequest $request
     * @param Keyword $keyword
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(KeywordRequest $request, Keyword $keyword)
    {
        $list = $keyword->list($this->user()->id, $request->input('app_id'), Constant::REPLY_RULE_SCENE_KEYWORD);

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
     * @param CreateKeywordRequest $request
     * @param Keyword $keyword
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateKeywordRequest $request, Keyword $keyword)
    {
        $params            = $request->all();
        $params['user_id'] = $this->user()->id;
        $res               = $keyword->store($params);
        if (is_numeric($res)) {
            return $this->suc(['id' => $res]);
        }

        return $this->err($res);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @param Keyword $keyword
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Keyword $keyword)
    {
        $data = $keyword->get($id);

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
     * @param Keyword $keyword
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, Keyword $keyword)
    {
        if ($keyword->update($id, $request->all())) {
            return $this->suc();
        }

        return $this->err();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param         $id
     * @param Keyword $keyword
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Keyword $keyword)
    {
        if ($keyword->destroy($id)) {
            return $this->suc();
        }

        return $this->err();
    }
}
