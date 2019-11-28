<?php
namespace App\Http\Controllers\Api\User\Shop;

use App\Utilities\Constant;
use App\Utilities\FeedBack;
use App\Entities\Shop\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CreateBrandRequest;
use App\Repositories\Shop\BrandRepositoryEloquent;

class BrandController extends Controller
{
    protected $repository;

    public function __construct(BrandRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $this->authorize('view', Brand::class);

        $list = $this->repository->get();

        return $this->suc(compact('list'));
    }

    public function create()
    {
        //
    }

    public function store(CreateBrandRequest $request)
    {
        $this->authorize('create', Brand::class);

        $name  = $request->input('name');
        $brand = $this->repository->findByField('name', $name);
        if ($brand->isEmpty()) {
            if ($brand = $this->repository->insert(['name' => $name])) {
                $brand = $this->repository->findByField('name', $name);
            } else {
                return $this->err(FeedBack::CREATE_FAIL);
            }
        }
        $brand = $brand->toArray();
        $brand = $brand[Constant::FLASE_ZERO];

        return $this->suc(compact('brand'));
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
