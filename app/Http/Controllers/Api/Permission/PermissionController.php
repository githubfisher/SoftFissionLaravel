<?php
namespace App\Http\Controllers\Api\Permission;

use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    protected $guard;
    protected $permission;

    public function __construct(\App\Http\Repositories\Permission\Permission $permission)
    {
        $this->permission = $permission;
        $this->guard      = Config('auth.defaults.guard');
    }

    public function index()
    {
        $list = $this->permission->list(Constant::PAGINATE_MIN, $this->guard);

        return $this->suc(compact('list'));
    }

    public function create(Request $request)
    {
        $role = $this->permission->create($request->input('name'), $this->guard);

        return $this->suc(compact('role'));
    }
}
