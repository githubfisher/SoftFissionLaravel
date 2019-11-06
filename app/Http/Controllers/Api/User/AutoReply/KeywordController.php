<?php

namespace App\Http\Controllers\Api\User\AutoReply;

use App\Http\Controllers\Controller;
use App\Http\Repositories\AutoReply\Keyword;
use App\Http\Requests\User\AutoReply\KeywordRequest;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Keyword $keyword
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Keyword $keyword)
    {
        $list = $keyword->list();

        return $this->suc(compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param KeywordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(KeywordRequest $request)
    {
        return $this->suc();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KeywordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(KeywordRequest $request)
    {
        return $this->suc();
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->suc([$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
