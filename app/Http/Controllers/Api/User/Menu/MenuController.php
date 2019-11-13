<?php
namespace App\Http\Controllers\Api\User\Menu;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Material\Voice;

class MenuController extends Controller
{
    protected $voice;

    public function __construct(Voice $voice)
    {
        $this->voice = $voice;
    }

    public function index()
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }
}
