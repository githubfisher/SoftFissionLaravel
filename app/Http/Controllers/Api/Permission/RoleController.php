<?php
namespace App\Http\Controllers\Api\Permission;

use Illuminate\Http\Request;
use App\Http\Utilities\Constant;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $guard;
    protected $role;

    public function __construct(\App\Http\Repositories\Permission\Role $role)
    {
        $this->role  = $role;
        $this->guard = Config('auth.defaults.guard');
    }

    public function index()
    {
        $list = $this->role->list(Constant::PAGINATE_MIN, $this->guard);

        return $this->suc(compact('list'));
    }

    public function create(Request $request)
    {
        $role = $this->role->create($request->input('name'), $this->guard);

        return $this->suc(compact('role'));
    }
}
