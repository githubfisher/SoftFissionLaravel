<?php
namespace App\Http\Controllers\Api\User\Shop;

use Illuminate\Http\Request;
use App\Entities\Shop\Project;
use App\Http\Controllers\Controller;
use App\Repositories\Shop\ProjectRepositoryEloquent;

class ProjectController extends Controller
{
    protected $repository;

    public function __construct(ProjectRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $this->authorize('view', Project::class);

        $list = $this->repository->get();

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $name    = $request->input('name');
        $project = $this->repository->findByField('name', $name);
        if ($project->isEmpty()) {
            $project = $this->repository->create(['name' => $name]);
        }

        return $this->suc(compact('project'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
