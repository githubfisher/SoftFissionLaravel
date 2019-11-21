<?php
namespace App\Http\Controllers\Api\User\OpenPlatform\Menu;

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
