<?php
namespace App\Http\Controllers\Api\User\Shop;

use App\Utilities\Constant;
use App\Utilities\FeedBack;
use Illuminate\Http\Request;
use App\Entities\Shop\Project;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CreateProjectRequest;
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

    public function store(CreateProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $name    = $request->input('name');
        $project = $this->repository->findByField('name', $name);
        if ($project->isEmpty()) {
            if ($project = $this->repository->insert(['name' => $name])) {
                $project = $this->repository->findByField('name', $name);
            } else {
                return $this->err(FeedBack::CREATE_FAIL);
            }
        }
        $project = $project->toArray();
        $project = $project[Constant::FLASE_ZERO];

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
