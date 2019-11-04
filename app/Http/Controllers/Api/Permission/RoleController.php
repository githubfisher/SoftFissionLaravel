<?php
namespace App\Http\Controllers\Api\Permission;

use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $list = Role::where('guard_name', $request->input('guard', 'user'))->simplePaginate(Constant::PAGINATE_MIN);

        return $this->suc(compact('list'));
    }

    public function create(Request $request)
    {
        $role = Role::create(['guard_name' => $request->input('guard', 'user'), 'name' => $request->input('name')]);

        return $this->suc(compact('role'));
    }

    public function delete(Request $request)
    {
        Role::destroy($request->input('name'));

        return $this->suc();
    }
}
